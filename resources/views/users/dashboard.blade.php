@extends('layouts.default')

@section('title', 'Dashboard')

@section('content')
    <div class="row align-items-center mb-2">
        <div class="col">
            <h2 class="h5 page-title">Welcome Back {{ session('name') }}!</h2>
        </div>
    </div>
    <div class="mb-2 align-items-center">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mt-1 align-items-center">
                    <div class="col-12 col-lg-4 text-left pl-4">
                        <p class="mb-1 small text-muted">Balance</p>
                        <span class="h3">$12,600</span>
                        <span class="small text-muted">+20%</span>
                        <span class="fe fe-arrow-up text-success fe-12"></span>
                        <p class="text-muted mt-2">Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui</p>
                    </div>
                    <div class="col-6 col-lg-2 text-center py-4">
                        <p class="mb-1 small text-muted">Today</p>
                        <span class="h3">$2600</span><br />
                        <span class="small text-muted">+20%</span>
                        <span class="fe fe-arrow-up text-success fe-12"></span>
                    </div>
                    <div class="col-6 col-lg-2 text-center py-4 mb-2">
                        <p class="mb-1 small text-muted">Goal Value</p>
                        <span class="h3">$260</span><br />
                        <span class="small text-muted">+6%</span>
                        <span class="fe fe-arrow-up text-success fe-12"></span>
                    </div>
                    <div class="col-6 col-lg-2 text-center py-4">
                        <p class="mb-1 small text-muted">Completions</p>
                        <span class="h3">26</span><br />
                        <span class="small text-muted">+20%</span>
                        <span class="fe fe-arrow-up text-success fe-12"></span>
                    </div>
                    <div class="col-6 col-lg-2 text-center py-4">
                        <p class="mb-1 small text-muted">Conversion</p>
                        <span class="h3">6%</span><br />
                        <span class="small text-muted">-2%</span>
                        <span class="fe fe-arrow-down text-danger fe-12"></span>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    <!-- Slider -->
    <div class="banner-slider">
        <div class="slider-container">
            <div class="slides">
                @php
                    $images = [
                        ['src' => 'https://picsum.photos/1500/1000', 'title' => 'Sunday Image Title', 'description' => 'Sunday Image Description', 'button' => 'Get Started', 'class' => 'sunday'],
                        ['src' => 'https://picsum.photos/1500/1001', 'title' => 'Monday Image Title', 'description' => 'Monday Image Description', 'button' => 'Get Started', 'class' => 'monday'],
                        ['src' => 'https://picsum.photos/1500/1002', 'title' => 'Tuesday Image Title', 'description' => 'Tuesday Image Description', 'button' => 'Get Started', 'class' => 'tuesday'],
                        ['src' => 'https://picsum.photos/1500/1003', 'title' => 'Wednesday Image Title', 'description' => 'Wednesday Image Description', 'button' => 'Get Started', 'class' => 'wednesday'],
                        ['src' => 'https://picsum.photos/1500/1004', 'title' => 'Thursday Image Title', 'description' => 'Thursday Image Description', 'button' => 'Get Started', 'class' => 'thursday'],
                        ['src' => 'https://picsum.photos/1500/1005', 'title' => 'Friday Image Title', 'description' => 'Friday Image Description', 'button' => 'Get Started', 'class' => 'friday'],
                        ['src' => 'https://picsum.photos/1500/1006', 'title' => 'Saturday Image Title', 'description' => 'Saturday Image Description', 'button' => 'Get Started', 'class' => 'saturday'],
                        ['src' => 'https://picsum.photos/1500/1007', 'title' => 'Daily Image Title', 'description' => 'Daily Image Description', 'button' => 'Learn more']
                    ];
                @endphp

                @foreach($images as $image)
                    <div class="slide {{ $image['class'] ?? '' }}">
                        <img src="{{ $image['src'] }}" alt="{{ $image['title'] }}">
                        <div class="slide-content" style="width:100%; height: 100%; display: flex;">
                            <div class="col-md-6">
                                <h2 class="text-white">{{ $image['title'] }}</h2>
                                <p class="text-white">{{ $image['description'] }}</p>
                            </div>
                            <div class="col-md-6">
                                @if(isset($image['button']))
                                    <button class="slide-button">{{ $image['button'] }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="slide-overlay left" onclick="moveSlide(-1)"></div>
            <div class="slide-overlay right" onclick="moveSlide(1)"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let slideIndex = 0;
            let autoSlideInterval;

            function showSlides() {
                const slides = $('.slides');
                const slideItems = $('.slide');
                if (slideItems.length === 0) return;

                const today = new Date().getDay();
                const isSunday = today === 0;
                const isMonday = today === 1;
                const isTuesday = today === 2;
                const isWednesday = today === 3;
                const isThursday = today === 4;
                const isFriday = today === 5;
                const isSaturday = today === 6;

                $('.slide').each(function() {
                    if ($(this).hasClass('sunday') && !isSunday) {
                        $(this).hide();
                    } else if ($(this).hasClass('monday') && !isMonday) {
                        $(this).hide();
                    } else if ($(this).hasClass('tuesday') && !isTuesday) {
                        $(this).hide();
                    } else if ($(this).hasClass('wednesday') && !isWednesday) {
                        $(this).hide();
                    } else if ($(this).hasClass('thursday') && !isThursday) {
                        $(this).hide();
                    } else if ($(this).hasClass('friday') && !isFriday) {
                        $(this).hide();
                    } else if ($(this).hasClass('saturday') && !isSaturday) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });

                if ($('.slide:visible').length === 0) return;

                slideIndex = (slideIndex + $('.slide:visible').length) % $('.slide:visible').length;
                slides.css('transform', `translateX(${-slideIndex * 100}%)`);
            }

            function moveSlide(n) {
                slideIndex = (slideIndex + n + $('.slide:visible').length) % $('.slide:visible').length;
                showSlides();
            }

            function startAutoSlide() {
                // alert('slide');
                // autoSlideInterval = setInterval(() => moveSlide(1), 5000);
            }

            function stopAutoSlide() {
                // alert('stop');
                clearInterval(autoSlideInterval);
            }

            $('.slide').hover(stopAutoSlide, startAutoSlide);

            $('.slide-overlay.left').click(function() {
                moveSlide(-1);
            });

            $('.slide-overlay.right').click(function() {
                moveSlide(1);
            });

            showSlides();
            startAutoSlide();
        });
    </script>
    <style>
        .banner-slider {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .slider-container {
            max-width: 100%;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            box-sizing: border-box;
            position: relative;
        }
        .slide img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }
        .slide-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            padding-top: 20px;
        }
        .text-white {
            color: #fff;
        }
        .slide-button {
            text-decoration: none !important;
            background-color: #007bff;
            border: none;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .slide-button:hover {
            color: white;
            background-color: #0056b3;
        }
        .slide-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 5%;
            cursor: pointer;
            z-index: 1000;
            /* border: 2px solid green; */
            border-radius: 50%;
        }
        .slide-overlay.left {
            left: 0;
        }
        .slide-overlay.right {
            right: 0;
        }
    </style>
@endsection
