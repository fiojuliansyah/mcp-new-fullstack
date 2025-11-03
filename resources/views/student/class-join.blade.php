@extends('layouts.app')

@section('title', 'Zoom Live Meeting')

@section('content')

    @php
        $topic = $schedule->topic ?? 'Sesi Pembelajaran Langsung';
        $startTime = \Carbon\Carbon::parse($schedule->time ?? now()->addMinutes(10));
        $tutorName = $schedule->classroom->user->name;
        $formattedTime = $startTime->format('H:i');
        $isLive = $startTime->isPast() && $startTime->diffInHours(now()) < 1;
        $statusText = $isLive ? 'LIVE' : 'MENANTI';
        $statusColor = $isLive ? 'bg-red-500' : 'bg-yellow-900';
    @endphp

    <section class="w-full bg-primary dark:bg-gray-900 min-h-screen py-10">
        <div class="container mx-auto px-4 max-w-screen-xl">
            
            <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border-t-4 border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                            Virtual Class
                        </span>
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white mt-1">
                            {{ $topic }}
                        </h1>
                        <p class="text-md text-gray-500 dark:text-gray-400 mt-2">
                            With: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $tutorName }}</span>
                        </p>
                    </div>

                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold leading-none text-white {{ $statusColor }}">
                            {{ $statusText }}
                        </span>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">
                            Time: {{ $formattedTime }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2">
                    <div class="bg-gray-900 rounded-2xl shadow-2xl overflow-hidden relative">
                        
                        <div class="relative w-full h-[80vh] min-h-[500px]">
                            
                            <div id="loading-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800 transition-opacity duration-500">
                                <svg class="animate-spin h-8 w-8 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-gray-300 font-medium">Loading Zoom connection...</p>
                                <p class="mt-2 text-xs text-gray-400">Make sure camera/microphone permissions are enabled.</p>
                            </div>

                            <iframe 
                                id="zoom-iframe"
                                src="{{ route('student.schedule.zoom', $schedule->id) }}"
                                frameborder="0" 
                                allow="microphone; camera; display-capture; fullscreen"
                                allowfullscreen
                                class="absolute top-0 left-0 w-full h-full"
                                title="Zoom Meeting Embed"
                                onload="document.getElementById('loading-overlay').classList.add('opacity-0', 'hidden')"
                            ></iframe>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 h-full border">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                           Joining Guide
                        </h3>
                        
                        <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="text-gray-500 font-bold mr-3">1.</span>
                                <p>Make sure that <b>camera and microphone permissions</b> in your browser are allowed for this domain.</p>
                            </li>
                            <li class="flex items-start">
                                <span class="text-gray-500 font-bold mr-3">2.</span>
                                <p>Use <b>Google Chrome, Safari</b> or <b>Mozilla Firefox</b> for the best experience.</p>
                            </li>
                            <li class="flex items-start">
                                <span class="text-gray-500 font-bold mr-3">3.</span>
                                <p>If the <b>embed</b> fails to load, use the button below to <b>open the meeting in a new window</b>.</p>
                            </li>
                        </ul>

                        <div class="mt-6 border-t pt-4">
                            <a href="{{ route('student.schedule.zoom', $schedule->id) }}" target="_blank"
                                class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Open in New Tab
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
