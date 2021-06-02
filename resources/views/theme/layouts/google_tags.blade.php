googletag.cmd.push(function() {
    @if (isset($googleAdTags) && !empty($googleAdTags))
        <?php $googleTags = array_flip($googleAdTags); ?>
        @if (isset($googleTags[1]))
        googletag.defineSlot('/17337359/Slot1', [728, 90], 'div-gpt-ad-1451989749397-0').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[2]))
        googletag.defineSlot('/17337359/Slot2', [300, 600], 'div-gpt-ad-1451989749397-1').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[3]))
        googletag.defineSlot('/17337359/Slot3', [300, 250], 'div-gpt-ad-1451989749397-2').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[4]))
        googletag.defineSlot('/17337359/Slot4', [728, 90], 'div-gpt-ad-1451989749397-3').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[5]))
        googletag.defineSlot('/17337359/Slot5', [300, 250], 'div-gpt-ad-1451989749397-4').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[6]))
        googletag.defineSlot('/17337359/Slot6', [300, 250], 'div-gpt-ad-1451989749397-5').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[7]))
        googletag.defineSlot('/17337359/Slot7', [300, 250], 'div-gpt-ad-1451989749397-6').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[8]))
        googletag.defineSlot('/17337359/Slot8', [300, 250], 'div-gpt-ad-1451989749397-7').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[9]))
        googletag.defineSlot('/17337359/Slot9', [300, 250], 'div-gpt-ad-1451989749397-8').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[10]))
        googletag.defineSlot('/17337359/Slot10', [728, 90], 'div-gpt-ad-1451989749397-9').addService(googletag.pubads());
        @endif
        @if (isset($googleTags[11]))
        googletag.defineSlot('/17337359/Slot11', [300, 250], 'div-gpt-ad-1451989749397-10').addService(googletag.pubads());
        @endif
        @if (\Config::get('dpoptions.side_dress.value') == 1)
            @if (isset($googleTags[12]))
            //googletag.defineSlot('/17337359/Slot12', [160, 600], 'div-gpt-ad-1451989749397-11').addService(googletag.pubads());
            googletag.defineSlot('/17337359/Slot12', [160, 600], 'div-gpt-ad-1454059901646-1').addService(googletag.pubads());
            @endif
            @if (isset($googleTags[13]))
            //googletag.defineSlot('/17337359/Slot13', [160, 600], 'div-gpt-ad-1451989749397-12').addService(googletag.pubads());
            googletag.defineSlot('/17337359/Slot13', [160, 600], 'div-gpt-ad-1454059901646-0').addService(googletag.pubads());
            @endif
        @endif
        @if (isset($googleTags[14]))
        googletag.defineSlot('/17337359/Slot14', [300, 600], 'div-gpt-ad-1452178345911-13').addService(googletag.pubads());
        @endif
        googletag.pubads().enableSingleRequest();
        googletag.enableServices();
    @else
    @endif
});
