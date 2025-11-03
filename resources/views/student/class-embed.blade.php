
<!DOCTYPE html>

<head>
    <title>Zoom Class - MCPLUS Premium</title>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="origin-trial" content="">
</head>

<body>
    <div class="container">
        <main id="zmmtg-root"></main>
    </div>

    <script src="https://source.zoom.us/4.0.0/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/4.0.0/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/4.0.0/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/4.0.0/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/4.0.0/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-4.0.0.min.js"></script>
        
    <script>
    ZoomMtg.preLoadWasm();
    ZoomMtg.prepareWebSDK();

    const scheduleId = "{{ $schedule->id }}";
    const sdkKey = "{{ env('ZOOM_SDK_KEY') }}";
    const meetingNumber = "{{ $schedule->zoom_meeting_id }}";
    const passWord = "{{ $schedule->password }}";
    const userName = "{{ Auth::user()->name }}";
    const userEmail = "{{ Auth::user()->email }}";
    const role = "{{ optional(Auth::user())->account_type == 'tutor' ? 1 : 0 }}";
    const leaveUrl = "{{ route('student.schedule.zoom', $schedule->id) }}";

    const joinUrl = "{{ route('student.attendance.join', $schedule->id) }}";
    const outUrl = "{{ route('student.attendance.out', $schedule->id) }}";

    function recordAttendance(endpoint) {
        return fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => console.log('Attendance recorded:', data))
        .catch(error => console.error('Attendance recording failed:', error));
    }

    function getSignature(meetingNumber, role) {
        return fetch("{{ route('zoom.signature') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ meetingNumber: meetingNumber, role: role })
        })
        .then(response => response.json())
        .then(data => data.signature);
    }

    getSignature(meetingNumber, role).then(signature => {
        if (!signature) {
            console.error("Failed to get signature.");
            return;
        }

        ZoomMtg.init({
            leaveUrl: leaveUrl,
            isSupportAV: true,
            success: function () {
                ZoomMtg.join({
                    signature: signature,
                    sdkKey: sdkKey,
                    meetingNumber: meetingNumber,
                    passWord: passWord,
                    userName: userName,
                    userEmail: userEmail,
                    success: function (res) {
                        console.log("Successfully joined the meeting:", res);
                        
                        recordAttendance(joinUrl);
                    },
                    error: function (res) {
                        console.error("Failed to join:", res);
                    }
                });
            },
            error: function (res) {
                console.error("SDK initialization failed:", res);
            }
        });
    });

    window.addEventListener('beforeunload', function (e) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', outUrl, false);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        
        try {
            xhr.send(JSON.stringify({}));
            console.log('Out time recorded synchronously.');
        } catch (e) {
            console.error('Synchronous out recording failed:', e);
        }
    });

</script>
</body>

</html>