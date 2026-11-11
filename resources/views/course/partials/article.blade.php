<div class="article-lesson">

    <div class="article-lesson-inner">

        <!-- Lesson Title -->
        <h3 class="article-lesson-title">
            {{ $lesson->title }}
        </h3>


        <!-- Lesson Content -->
        <div class="article-lesson-content">
            {!! $lesson->content ?? 'No content available.' !!}
        </div>

    </div>

</div>


@push('styles')

<style>

/* =========================================================
   ARTICLE LESSON
========================================================= */

.article-lesson {
    width: 100%;
    background: #ffffff;
}


.article-lesson-inner {
    padding: 32px;
}


/* =========================================================
   TITLE
========================================================= */

.article-lesson-title {
    margin: 0 0 28px;

    color: #212529;

    font-size: 1.75rem;

    line-height: 1.3;

    font-weight: 700;

    word-break: break-word;
}


/* =========================================================
   CONTENT
========================================================= */

.article-lesson-content {
    color: #6c757d;

    font-size: 1rem;

    line-height: 1.8;

    overflow-wrap: break-word;

    word-wrap: break-word;

    word-break: normal;
}


/* =========================================================
   HEADINGS INSIDE ARTICLE
========================================================= */

.article-lesson-content h1,
.article-lesson-content h2,
.article-lesson-content h3,
.article-lesson-content h4,
.article-lesson-content h5,
.article-lesson-content h6 {
    color: #212529;

    font-weight: 700;

    line-height: 1.35;

    margin-top: 1.8rem;

    margin-bottom: 0.8rem;

    word-break: break-word;
}


.article-lesson-content h1 {
    font-size: 1.8rem;
}


.article-lesson-content h2 {
    font-size: 1.55rem;
}


.article-lesson-content h3 {
    font-size: 1.35rem;
}


.article-lesson-content h4 {
    font-size: 1.2rem;
}


/* =========================================================
   PARAGRAPHS
========================================================= */

.article-lesson-content p {
    margin-bottom: 1rem;
}


/* =========================================================
   IMAGES
========================================================= */

.article-lesson-content img {
    display: block;

    max-width: 100% !important;

    width: auto;

    height: auto;

    margin: 1.25rem auto;

    border-radius: 10px;
}


/* =========================================================
   VIDEOS / IFRAMES
========================================================= */

.article-lesson-content iframe,
.article-lesson-content video,
.article-lesson-content embed,
.article-lesson-content object {
    display: block;

    max-width: 100% !important;

    width: 100%;

    margin: 1.25rem auto;

    border: 0;

    border-radius: 10px;
}


/* =========================================================
   LISTS
========================================================= */

.article-lesson-content ul,
.article-lesson-content ol {
    padding-left: 1.5rem;

    margin-bottom: 1rem;
}


.article-lesson-content li {
    margin-bottom: 0.45rem;
}


/* =========================================================
   LINKS
========================================================= */

.article-lesson-content a {
    overflow-wrap: anywhere;

    word-break: break-word;
}


/* =========================================================
   BLOCKQUOTE
========================================================= */

.article-lesson-content blockquote {
    margin: 1.25rem 0;

    padding: 1rem 1.25rem;

    border-left: 4px solid #05d5b3;

    background: #f8f9fa;

    border-radius: 0 8px 8px 0;
}


/* =========================================================
   CODE
========================================================= */

.article-lesson-content pre {
    max-width: 100%;

    overflow-x: auto;

    padding: 1rem;

    background: #212529;

    color: #ffffff;

    border-radius: 8px;

    -webkit-overflow-scrolling: touch;
}


.article-lesson-content code {
    overflow-wrap: break-word;
}


/* =========================================================
   TABLES
========================================================= */

.article-lesson-content table {
    display: block;

    width: 100% !important;

    max-width: 100%;

    overflow-x: auto;

    border-collapse: collapse;

    -webkit-overflow-scrolling: touch;
}


.article-lesson-content th,
.article-lesson-content td {
    padding: 8px 12px;

    white-space: nowrap;
}


/* =========================================================
   HORIZONTAL RULE
========================================================= */

.article-lesson-content hr {
    margin: 1.5rem 0;

    border: 0;

    border-top: 1px solid #dee2e6;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767.98px) {

    .article-lesson {
        width: 100%;

        border-radius: 0 !important;

        border-left: 0;

        border-right: 0;

        box-shadow: none;
    }


    .article-lesson-inner {
        padding: 22px 16px 30px;
    }


    /* Title */

    .article-lesson-title {
        font-size: 1.35rem;

        line-height: 1.35;

        margin-bottom: 20px;
    }


    /* Content */

    .article-lesson-content {
        font-size: 0.95rem;

        line-height: 1.75;
    }


    /* Headings */

    .article-lesson-content h1 {
        font-size: 1.5rem;
    }


    .article-lesson-content h2 {
        font-size: 1.3rem;
    }


    .article-lesson-content h3 {
        font-size: 1.15rem;
    }


    .article-lesson-content h4 {
        font-size: 1.05rem;
    }


    .article-lesson-content h1,
    .article-lesson-content h2,
    .article-lesson-content h3,
    .article-lesson-content h4,
    .article-lesson-content h5,
    .article-lesson-content h6 {
        margin-top: 1.4rem;

        margin-bottom: 0.7rem;
    }


    /* Images */

    .article-lesson-content img {
        width: 100% !important;

        max-width: 100% !important;

        height: auto !important;

        margin: 1rem 0;

        border-radius: 8px;
    }


    /* Lists */

    .article-lesson-content ul,
    .article-lesson-content ol {
        padding-left: 1.25rem;
    }


    /* Blockquote */

    .article-lesson-content blockquote {
        padding: 0.8rem 1rem;

        margin: 1rem 0;
    }


    /* Code */

    .article-lesson-content pre {
        margin-left: 0;

        margin-right: 0;

        padding: 0.8rem;

        font-size: 0.8rem;

        border-radius: 7px;
    }


    /* Tables */

    .article-lesson-content table {
        font-size: 0.85rem;
    }


    .article-lesson-content th,
    .article-lesson-content td {
        padding: 7px 10px;
    }

}


/* =========================================================
   VERY SMALL PHONES
========================================================= */

@media (max-width: 375px) {

    .article-lesson-inner {
        padding: 18px 13px 28px;
    }


    .article-lesson-title {
        font-size: 1.25rem;

        margin-bottom: 17px;
    }


    .article-lesson-content {
        font-size: 0.92rem;

        line-height: 1.7;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    let isCompleted = false;


    function markLessonComplete() {

        if (isCompleted) {
            return;
        }


        isCompleted = true;


        fetch("{{ route('lessons.complete', $lesson->id) }}", {

            method: 'POST',

            headers: {

                'X-CSRF-TOKEN': '{{ csrf_token() }}',

                'Content-Type': 'application/json',

                'Accept': 'application/json'

            }

        })

        .then(response => response.json())

        .then(data => {

            if (data.success) {

                console.log(
                    '[Lesson Completed Automatically]'
                );


                if (data.redirect_url) {

                    window.location.href =
                        data.redirect_url;

                }

            }

        })

        .catch(error => {

            console.error(
                'Error auto-completing lesson:',
                error
            );

            isCompleted = false;

        });

    }


    /*
     * Article lessons are completed immediately
     * when the lesson is opened.
     */
    markLessonComplete();

});

</script>

@endpush