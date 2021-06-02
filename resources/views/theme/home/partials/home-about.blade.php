<!-- HOME ABOUT -->
@if (!empty($block_about[0]['featured']) && isset($block_about[0]['featured'][0]) && isset($block_about[0]['featured'][0]['media']) && !empty($block_about[0]['featured'][0]['media']))
    <?php $backis = $frontHelp->pImg($block_about[0], 'about-block'); ?>
@else
    <?php $backis = cdn('/theme/assets/img/about-bg.jpg'); ?>
@endif
<div id="home-about" class="hidden-xs">
    <div class="about-container" style="background: url('{{ $backis }}') no-repeat top center scroll;">
        <div class="aleft">
        </div>
        <div class="aright">
            <!-- <div class="blueoverlay"></div> -->
            <div class="about-content">
            @if(isset($block_about) && isset($block_about[0]))
                <h2>{{ $block_about[0]->title }}</h2>
                {!! $block_about[0]->body !!}
            	<a href="{{ $block_about[0]['ext_url'] }}" title="{{ $block_about[0]->title }}" class="btn btn-green-invert">LEARN MORE</a>
			@endif
        	</div>
        </div>
    </div>
</div>
<!-- HOME ABOUT END -->

