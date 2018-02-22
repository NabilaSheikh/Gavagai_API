<!DOCTYPE html>
<html>
   <head>
      <?php $varhttp= "https://";?>
      <!-- CSS start -->
      <link rel="stylesheet" type="text/css" href="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>css/main.css" />
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- CSS end -->
   </head>
   <body>
      <div class="centre">
         <h2>Welcome To Gavagai Api
         </h2>
      </div>
      <div class="container centre">
         <div class="alert alert-warning" id="warning_notification" hidden>
            <strong>Warning!</strong> All fields are compulsory !.
         </div>
      </div>
      <div class="container centre container-div-main">
         <div class="row">
            <div class="col-sm-3 form-group">
               Please select your file
            </div>
            <div class="col-sm-3 form-group">
               <input type="file" id="myFile" accept=".pdf,.txt">
            </div>
         </div>
         <div class="row">
            <div class="col-sm-3 form-group">
               Please select langage
            </div>
            <div class="col-sm-3 form-group">
               <select class="form-control" id="languagesInList">
               </select>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-2 form-group">
            </div>
            <div class="col-sm-3 form-group">
               <a class="btn icon-btn btn-info" onclick="uploadFile()" href="#">
               <span class="glyphicon btn-glyphicon glyphicon-share img-circle text-info"></span>
               Call API
               </a>
            </div>
         </div>
      </div>
      <div id='myChart'><a class="zc-ref" href="https://www.zingchart.com/">Charts by ZingChart</a>
      </div>
   </body>
   <input type="hidden" id="uploaded_file_name" value="">
</html>
<!-- JS start -->
<script type="text/javascript" src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/jquery-2.0.3.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<script type="text/javascript" src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/bootstrap-filestyle.min.js"></script> 
<script src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/pdf.js"></script>
<script>
   var base_url="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>";
</script>
<script src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/custom.js"></script>
<!-- JS end -->