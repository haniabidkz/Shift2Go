
<style type="text/css">
    #signatureparent {
        color:black;
        background-color:white;
        /*max-width:600px;*/
        padding:20px;
    }

    /*This is the div within which the signature canvas is fitted*/
    #signature {
        border: 2px dotted black;
        background-color: white;
        width: 355px !important;
        height: 200px !important;
    }

    /* Drawing the 'gripper' for touch-enabled devices */
    html.touch #content {
        float:left;
        width:92%;

    }
    html.touch #scrollgrabber {
        float:right;
        width:4%;
        margin-right:2%;

    }
    html.borderradius #scrollgrabber {
        border-radius: 1em;
    }

</style>

{{ Form::model($contract, array('route' => array('contract.signaturedata', $contract->id), 'method' => 'PUT', 'class' => 'form_sub')) }}
 <div class="form-group col-md-6">
        <input type="hidden" name="owner_signature" class="signature_value">
            <div id="content">
                <div id="signatureparent">
                    <div id="signature" >

                    </div>


                </div>
                <div id="tools"></div>

        </div>
        <div id="scrollgrabber"></div>

    </div>

<div class="card-footer text-end border-0 p-0">
    <button type="submit" class="btn btn-primary save_btn_signature">
        {{__('Save Changes')}}
    </button>

</div>
</form>
@if(isset($flag) && $flag == 'true')

<!-- <script src="{{asset('custom/libs/src/modernizr.js')}}"></script> -->
    <script src="{{asset('custom/libs/src/jSignature.js')}}"></script>
    <script src="{{asset('custom/libs/src/plugins/jSignature.CompressorBase30.js')}}"></script>
    <script src="{{asset('custom/libs/src/plugins/jSignature.CompressorSVG.js')}}"></script>
    <script src="{{asset('custom/libs/src/plugins/jSignature.UndoButton.js')}}"></script>
    <script src="{{asset('custom/libs/src/plugins/signhere/jSignature.SignHere.js')}}"></script>

@endif

<script>

    var topics = {};
    $.publish = function(topic, args) {
        if (topics[topic]) {
            var currentTopic = topics[topic],
            args = args || {};

            for (var i = 0, j = currentTopic.length; i < j; i++) {
                currentTopic[i].call($, args);
            }
        }
    };
    $.subscribe = function(topic, callback) {
        if (!topics[topic]) {
            topics[topic] = [];
        }
        topics[topic].push(callback);
        return {
            "topic": topic,
            "callback": callback
        };
    };
    $.unsubscribe = function(handle) {
        var topic = handle.topic;
        if (topics[topic]) {
            var currentTopic = topics[topic];

            for (var i = 0, j = currentTopic.length; i < j; i++) {
                if (currentTopic[i] === handle.callback) {
                    currentTopic.splice(i, 1);
                }
            }
        }
    };

</script>

<script type="text/javascript">
  // $(document).ready(function() {
    // This is the part where jSignature is initialized.
    var $sigdiv = $("#signature").jSignature({'UndoButton':true})
    // All the code below is just code driving the demo.
    , $tools = $('#tools')
    , $extraarea = $('#displayarea')
    , pubsubprefix = 'jSignature.demo.'

    $(".save_btn_signature").click(function(e){

        e.preventDefault(e);
       var data = $sigdiv.jSignature('getData', 'svg')

        $('textarea', $tools).val(data.join(','))
        $.publish(pubsubprefix + data[0], data);
        $('.signature_value').val(data[1]);
        try {
            $('.signature_value', $tools).val(JSON.stringify(data))
        } catch (ex) {
            $('.signature_value', $tools).val('Not sure how to stringify this, likely binary, format.')
        }

        $('.form_sub').submit();

    });


    // if (Modernizr.touch){
    //     $('#scrollgrabber').height($('#content').height())
    // }


</script>
