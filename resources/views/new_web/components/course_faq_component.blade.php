@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $topics = $dynamic_page_data["topics"] ?? null;
    $questions = $dynamic_page_data["questions"] ?? null;
    $faqs = $dynamic_page_data["faqs"] ?? null;
    $title = '';
    $body = '';
    if(isset($sections['questions'])){
        $title = $sections['questions']->first()->title ?? null;
        $body = $sections['questions']->first()->description ?? null;
    }
@endphp

<div class="course-full-text mt-5 mb-5">
<h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
<h3 class="text-align-center text-xs-left tab-title">{!!$body!!}</h3>

@if (count($faqs) > 0)

<?php $f=[] ?>
@foreach ($faqs as $key => $row)

<h3 class="tab-sub-title">{!! $key !!}</h3>
<div class="tab-faq-wrapper multiple-accordions">
    <div class="accordion-wrapper">


        @foreach ($row as $qkey => $qna)
        <?php $questions = [];?>

        <div class="accordion-item">
            <h4 class="accordion-title title-blue-gradient scroll-to-top">{!! $qna['question'] !!}</h4>
            <div class="accordion-content">
            <div class="shorten-content">
            {!! $qna['answer'] !!}
            </div>
            </div>
            <!-- /.accordion-item -->
        </div>


        <?php
            $qq = [];
            $title = $qna['question'];
            $quest = $qna['answer'];

            $questions['@type'] = "Question";
            $questions['name'] = $title;
            $qq["@type"] = "Answer";
            $qq["text"] = $quest;

            $questions["acceptedAnswer"] = $qq;//json_encode($qq);

            $f[]= $questions; //json_encode($questions);


        ?>

        @endforeach






        <!-- /.accordion-wrapper -->
    </div>
    <!-- /.tab-faq-wrapper -->
</div>


@endforeach
<?php $f=json_encode($f);?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": {!!$f!!}
        }
            </script>
@endif
<!-- /.course-full-text -->
</div>