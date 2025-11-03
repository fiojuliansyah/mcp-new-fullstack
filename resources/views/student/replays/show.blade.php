@extends('layouts.app')

@section('title', 'Replay: ' . ($replay->title ?? 'Video'))

@section('content')

    <section class="w-full bg-primary text-white px-4 py-10">
        <div class="container mx-auto px-4 py-8 max-w-screen-xl">
            <div class="flex flex-col lg:flex-row justify-between gap-5 lg:items-end border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="/frontend/assets/icons/replay.svg" alt="Tutor Avatar" class="w-18" />
                    <div>
                        <span class="text-gray-250">Replay Video</span>
                        <h1 class="text-4xl font-bold tracking-tight text-white">Happy Watching!</h1>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-10">

                <div class="lg:col-span-2">
                    <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden">
                        @if ($replay->replayVideos->isNotEmpty())
                            @php
                                $requestedVideoId = request('video_id');
                                $currentVideo =
                                    $replay->replayVideos->first(fn($v) => $v->id == $requestedVideoId) ??
                                    $replay->replayVideos->first();
                            @endphp
                            <div id="video-player-container"
                                class="aspect-video bg-black flex flex-col items-center justify-center relative">
                                @if ($currentVideo->video_url)
                                    <video id="main-video" controls class="w-full h-full"
                                        poster="{{ $currentVideo->thumbnail_url ?? '' }}">
                                        <source src="{{ asset('storage/' . $currentVideo->video_url) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>

                                    <div id="live-progress-bar" class="absolute bottom-0 left-0 w-full bg-gray-700 h-2">
                                        <div id="progress-inner" class="bg-indigo-500 h-2 w-0 transition-all duration-300">
                                        </div>
                                    </div>
                                @else
                                    <p class="text-white text-lg">Video Player - URL Not Found</p>
                                @endif
                            </div>


                            <div class="p-4 bg-white text-gray-800">
                                <h2 id="video-title" class="text-xl font-semibold">{{ $currentVideo->title }}</h2>
                                <p id="video-description" class="text-sm text-gray-600 mt-2">
                                    {{ $replay->schedule->topic ?? 'No description available.' }}</p>
                                <p id="video-description" class="text-sm text-gray-600 mt-2">
                                    {{ $replay->schedule->agenda ?? 'No description available.' }}</p>
                            </div>
                        @else
                            <div class="p-8 bg-white text-center">
                                <p class="text-gray-600">No videos found in this replay series.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-primary rounded-xl shadow-lg p-4">
                        <h3 class="text-xl font-semibold text-white mb-4">Playlist ({{ $replay->replayVideos->count() }}
                            Videos)</h3>

                        <div class="space-y-3 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            @forelse ($replay->replayVideos as $video)
                                @php
                                    $isActive = $currentVideo->id === $video->id;
                                @endphp
                                <a href="{{ route('student.replay.show', ['replay' => $replay->id, 'video_id' => $video->id]) }}"
                                    class="block relative" >

                                    <div class="flex-shrink-0 mr-3 mb-4">
                                        <img data-video-thumb="{{ asset('storage/replays/' . basename($video->video_url)) }}"
                                            class="w-full aspect-video object-cover rounded-md" />
                                    </div>

                                    @if ($isActive)
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center rounded-md">
                                            <span class="text-white text-sm font-bold tracking-wider uppercase">
                                                Playing Now
                                            </span>
                                        </div>
                                    @endif
                                    
                                </a>
                            @empty
                                <p class="text-gray-500">No videos in this playlist.</p>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>


        </div>

    </section>

@endsection

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('main-video');
            if (!video) return;

            const replayVideoId = "{{ $currentVideo->id ?? '' }}";
            const resumePosition = {{ $resumePosition ?? 0 }};
            const trackUrl = `{{ route('student.replay.track-progress', ':id') }}`.replace(':id', replayVideoId);

            console.log('🎥 Video ID:', replayVideoId);
            console.log('⏪ Resume position:', resumePosition);

            video.addEventListener('loadedmetadata', () => {
                if (resumePosition > 5 && resumePosition < video.duration - 5) {
                    video.currentTime = resumePosition;
                    console.log(`🔁 Resuming video at ${resumePosition}s`);
                }
            });

            let lastSent = 0;

            video.addEventListener('timeupdate', () => {
                const currentTime = Math.floor(video.currentTime);
                const duration = Math.floor(video.duration || 0);

                if (currentTime - lastSent >= 10) {
                    lastSent = currentTime;

                    fetch(trackUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                current_time: currentTime,
                                duration: duration
                            })
                        })
                        .then(r => r.json())
                        .then(data => console.log('✅ Progress updated', data))
                        .catch(err => console.error('❌ Progress update failed', err));
                }
            });

            video.addEventListener('ended', () => {
                fetch(trackUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            current_time: Math.floor(video.duration),
                            duration: Math.floor(video.duration)
                        })
                    })
                    .then(() => console.log('🏁 Video ended — marked complete'));
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-video-thumb]').forEach(img => {
                const video = document.createElement('video');
                video.src = img.dataset.videoThumb;
                video.crossOrigin = 'anonymous';
                video.muted = true;
                video.playsInline = true;
                video.preload = 'metadata';
                video.addEventListener('loadedmetadata', () => {
                    video.currentTime = 0.5;
                });
                video.addEventListener('seeked', () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    img.src = canvas.toDataURL('image/jpeg');
                    video.remove();
                });
                video.addEventListener('error', () => {
                    img.src = '/frontend/assets/images/sample/image-1.png';
                });
            });
        });
    </script>
@endpush
