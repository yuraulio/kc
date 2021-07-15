<?php $prevId = (isset($id) ? $id : 0); $prevType = (isset($type) ? $type : "none"); $currStatus = (isset($status) ? $status : 1); $previewSession = session()->get('preview_session', []); ?>
@if ($previewSession && isset($previewSession[$prevType]) && isset($previewSession[$prevType][$prevId]))
<style>
.prevWarnCont { position: fixed; top: 20px; right: 20%; left: 20%; height: 80px; background-color: #cecece; color: #fff; z-index: 9999999; border: 1px solid #aaa; }
.prevWarnCont .prevWarnInner {}
.prevWarnInner .prevWarnMess { text-align: center; font-size: 1.4em; color: #333; line-height: 1.7em; font-weight: bolder; }
.prevWarnInner .prevWarnTools { text-align: center; }
.prevWarnTools .publishPrev { display: inline-block; width: 48%; margin: 2px; }
.prevWarnTools .dismissPrev { display: inline-block; width: 48%; margin: 2px; }
</style>
<?php dd('as'); ?>
<div class="prevWarnCont">
    <div class="prevWarnInner">
        <div class="prevWarnMess">Βλέπετε μια προεπισκόπιση του περιεχομένου</div>
        <div class="prevWarnTools">
            {{-- @if ($currStatus) --}}
            <span class="btn btn-warning publishPrev" data-dp-id="{{ $prevId }}" data-dp-type="{{ $prevType }}">Publish</span>
            {{-- @endif --}}
            <span class="btn btn-danger dismissPrev" data-dp-id="{{ $prevId }}" data-dp-type="{{ $prevType }}">Dismiss</span>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function () {
    $(".publishPrev").on("click", function () {
        var sbtObj = { op_type: "publish", id: $(this).attr("data-dp-id"), type: $(this).attr("data-dp-type") }
        $.ajax({ url: "/preview/session-tools", type: "post",
            data: sbtObj,
            success: function(data) {
                if (Number(data.status) === 1) {
                    window.location.reload();
                }
            }
        });
    });
    $(".dismissPrev").on("click", function () {
        var sbtObj = { op_type: "dismiss", id: $(this).attr("data-dp-id"), type: $(this).attr("data-dp-type") }
        $.ajax({ url: "/preview/session-tools", type: "post",
            data: sbtObj,
            success: function(data) {
                if (Number(data.status) === 1) {
                    window.close();
                }
            }
        });
    });
})
</script>
@endif
