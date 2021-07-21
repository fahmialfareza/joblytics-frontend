@extends('master')

@section('header')
    <script type="text/javascript" src="{{asset('assets/js/plugins/media/fancybox.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>
@endsection
@section('page-bar')
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <i class="icon-user-tie position-left"></i>
                <span class="text-semibold">Future of Jobs</span>
            </h4>
        </div>
    </div>
@endsection

@section('content')
    <div class="row" id="post-container">
        {{-- @for ($i = 0; $i < 9; $i++)
        <div class="col-lg-3 col-sm-6">
            <div class="thumbnail">
                <div class="thumb">
                    <img src="assets/images/placeholder.jpg" alt="">
                    <div class="caption-overflow">
                        <span>
                            <a href="assets/images/placeholder.jpg" data-popup="lightbox" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
                            <a href="gallery_description.html#" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-link2"></i></a>
                        </span>
                    </div>
                </div>

                <div class="caption">
                    <h6 class="no-margin-top text-semibold"><a href="gallery_description.html#" class="text-default">He it otherwise</a> <a href="gallery_description.html#" class="text-muted"><i class="icon-download pull-right"></i></a></h6>
                    Chapter too parties its letters nor. Cheerful but whatever ladyship disposed judgment us
                </div>
            </div>
        </div>
        @endfor --}}
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function () {
            // Initialize lightbox
            $('[data-popup="lightbox"]').fancybox({
                padding: 3
            });  
        })

        let $futureJobs

        $.ajax({
            url: 'api/future-job/list',
            method: 'get',
            success: function (res) {
                $futureJobs = res.result.data
                let futureJobHtml = ``
                $futureJobs.forEach(post => {
                    futureJobHtml +=
                    `<div class="col-lg-4">
                        <div class="thumbnail">
                            <div class="thumb" height="500px">
                                <img src="${post.image}" alt="${post.title}">
                                <div class="caption-overflow">
                                    <span>
                                        <a href="${post.image}" data-popup="lightbox" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-plus3"></i></a>
                                        <a href="${post.link}" target="_blank" rel="noopener" rel="noreferrer" class="btn border-white text-white btn-flat btn-icon btn-rounded ml-5"><i class="icon-hyperlink"></i></a>
                                    </span>
                                </div>
                            </div>

                            <div class="caption">
                                <h6 class="no-margin-top text-semibold"><a href="${post.link}" target="_blank" rel="noopener" rel="noreferrer" class="text-default">${post.title}</a> <a href="${post.link}" class="text-muted"><i class="icon-hyperlink pull-right"></i></a></h6>
                                ${post.source}
                            </div>
                        </div>
                    </div>`
                });
                $('#post-container').html(futureJobHtml)
            },
            error: function(err) {
                console.error(err);
            }
        })
    </script>
@endsection
