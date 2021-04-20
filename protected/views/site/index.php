<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl?>/css/style.css">
  <div class="row">
	<div class="col-sm-4">
		<div class="boxUpload">
		<h3>Bước 1: Tải ảnh lên</h3>
			<div class="section">
				<form enctype="multipart/form-data" action="" method="post" id="formUpload">
					<img src="<?php echo Yii::app()->baseUrl?>/images/icon-upload.png" width="80"/>
					<input type="file" name="img" id="imgUpload">
					<p>Kéo thả hoặc click vào ảnh để upload</p>
				</form>
			</div>
			<h3>Bước 2: Nhập chữ muốn hiển thị</h3>
			<p>Nhập chữ mà bạn muốn in lên sản phẩm</p>
			<input type="text" name="txtText" class="form-control" id="uploadTxt">
			<div id="font" style="display:none;">
				<p>Chọn font size</p>
				<select id="" class="form-control">
					<option value="14px">14px</option>
					<option value="16px">16px</option>
					<option value="18px">18px</option>
					<option value="20px">20px</option>
					<option value="24px">24px</option>
					<option value="28px">28px</option>
					<option value="30px">30px</option>
					<option value="36px">36px</option>
					<option value="48px">48px</option>
					<option value="72px">72px</option>
				</select>
			</div>
			<div id="fontFamily" style="display:none;">
				<p>Chọn font family</p>
				<select id="" class="form-control">
					<option value="Arial">Arial</option>
					<option value="Times New Roman">Times New Roman</option>
					<option value="Verdana">Verdana</option>
					<option value="Helvetica">Helvetica</option>
				</select>
			</div>
			<h3>Bước 3: Đặt hàng</h3>
			<button class="btn" type="button" id="submit">Đặt hàng</button>
			<a href="#" id="reset">Cài đặt lại</a>
		</div>
	</div>
	<div class="col-sm-6">
		<h3>Xem trước ảnh thiết kế</h3>
		<div class="row">
			<div class="col-md-12">
				<div id="drag1" class="drag" style="background-position:center;">
					<img class="img" width="100%" src="<?php echo Yii::app()->baseUrl?>/images/sp1.png" />
				</div>
	               <div id="drag2" class="drag">
					<img class="img" width="100%" src="<?php echo Yii::app()->baseUrl?>/images/sp2.png" />
				</div>
				<div id="drag3" class="drag" style="background-position:center;">
					<img class="img" width="100%" src="<?php echo Yii::app()->baseUrl?>/images/sp3.png" />
				</div>
	               <div id="drag4" class="drag">
					<img class="img" width="100%" src="<?php echo Yii::app()->baseUrl?>/images/sp4.png" />
				</div>
		     </div>
			<hr class="col-md-12">
			<div class="col-md-8" id="result">
				<div id="imgDesign">
					<img src="<?php echo Yii::app()->baseUrl?>/images/bg1.png" class="img-responsive"/>
				</div>
				<div class="rect" id="mydiv">
					<span id="removeImg"/></span>
				</div>
				<div class="" id="myTxt"></div>
			</div>
			<div class="col-md-4">
				<ul class="listimg">
					<li class="active"><img src="<?php echo Yii::app()->baseUrl?>/images/bg1.png" class="img-responsive"/></li>
					<li><img src="<?php echo Yii::app()->baseUrl?>/images/bg2.jpg" class="img-responsive"/></li>
					<li><img src="<?php echo Yii::app()->baseUrl?>/images/bg3.png" class="img-responsive"/></li>
				</ul>
			</div>
		</div>
		<div id="previewImage"></div>
	</div>
</div>
<?php Yii::app()->ClientScript->registerScript('drag', '
$(document).ready(function (e) {
	$("#uploadTxt").blur(function(){
		$("#myTxt").text($(this).val());
		$("#font").show();
		$("#fontFamily").show();
	});
	$("#font").find("select").change(function(){
		$("#myTxt").css("font-size", $(this).val())
	})
	$("#fontFamily").find("select").change(function(){
		$("#myTxt").css("font-family", $(this).val())
	})
	$(".listimg").find("li").each(function(i, j){
		$(j).click(function(){
			$(".listimg").find("li").removeClass("active")
			$(j).addClass("active")
			let im = $(j).html();
			$("#imgDesign").html(im);
		})
	})
 $("#formUpload").on("change",(function(e) {
  e.preventDefault();
  $.ajax({
     url: "'.Yii::app()->baseUrl.'/index.php?r=site/uploadimg",
   	type: "POST",
   	data:  new FormData(this),
   	contentType: false,
     cache: false,
   	processData:false,
   	beforeSend : function(){
   	},
   	success: function(data){
		$("#mydiv").show();
	    if(data=="invalid"){
	     // invalid file format.
	     $("#err").html("Invalid File !").fadeIn();
	    }else{
	     // view uploaded file.

	     $("#mydiv").append(data).fadeIn();
		$("#mydiv").click(function(){
				$("#removeImg").css("display","block");
		})
	     $("#formUpload")[0].reset();
		$("#removeImg").click(function(){
			$(this).next().remove();
			$(this).parent().hide();
		})
	    }
     },
     error: function(e){
    		$("#err").html(e).fadeIn();
     }
    });
 }));
});
//convert div to img
var element = $("#result"); // global variable
var getCanvas; // global variable
$("#submit").click(function(){
  html2canvas(element, {
    onrendered: function (canvas) {
      $("#previewImage").html(canvas);
      getCanvas = canvas;
    }
  });
});
$("#download").on("click", function () {
  var imgageData = getCanvas.toDataURL("image/png");
  // Now browser starts downloading it instead of just showing it
  var newData = imageData.replace(/^data:image\/png/, "data:application/octet-stream");
  $("#download").attr("download", "image.png").attr("href", newData);
});

//new
var $myDiv = $("#mydiv");
$myDiv.clayfy({
    type : "resizable",
    container : "#result",
    minSize : [30,30],
    //maxSize : [600,300],
    preserveAspectRatio: true,
    callbacks : {
        resize : function(){

        }
    }
});
//$("#mydiv").rotatable();
var $myDiv2 = $("#myTxt");
$myDiv2.clayfy({
    type : "draggable",
    container : "#result",
    minSize : [30,30]
});
//gallery
$(document).ready(function () {
    var x = null;
    //Make element draggable
    $(".drag").draggable({
        helper: "clone",
        cursor: "move",
        tolerance: "fit",
		stack: ".drag",
		revert: "invalid",
		dropoutside:true
    });
  $("#result").droppable({
      drop: function (e, ui) {
          if ($(ui.draggable)[0].id != "") {
              x = ui.helper.clone();
              ui.helper.remove();
	          x.draggable({
	              //helper: "original",
	              containment: "#result",
	              tolerance: "fit",
				stack: ".drag",
				droppable: "clayfy-drop"
	          });
	          x.resizable({
	            //animate: true,
	            aspectRatio: true,
	            helper: "ui-resizable-helper",
	            handles: "ne, nw, sw, se, re"
	          });
	          x.appendTo("#result");



			$(".ui-resizable-re").click(function(){
				$(this).parent().remove();
			})
	      }
      }
  });
});
$(document).click(function(e) {
    // matches all children of droppable, change selector as needed
    if( $(e.target).closest(".drag").length > 0 ) {
        $(e.target).closest(".drag").find(".ui-resizable-handle").show();
    }else {
        $("#result").find(".ui-resizable-handle").hide();
    }
});
', CClientScript::POS_END);
?>
