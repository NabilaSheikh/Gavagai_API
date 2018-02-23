<?php header('Access-Control-Allow-Origin: *'); ?>
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
            <strong>Warning!</strong> All fields are compulsory !!!.
         </div>
         <div class="alert alert-danger" id="warning_notification_general" hidden>
            <strong>Error!</strong> Something went bad with the API or Api key limit exceeded !!!.
         </div>
         <div class="alert alert-danger" id="warning_notification_file_size" hidden>
            <strong>Error!</strong> File size should be less than 2 MB !!!.
         </div>
         <div class="alert alert-danger" id="warning_notification_file_ext" hidden>
            <strong>Error!</strong> We Only support pdf / txt files !!!.
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
            <div class="col-sm-3 form-group">
               Please Enter your API Key
            </div>
            <div class="col-sm-3 form-group">
               <input type="text" id="myApiKey" class="form-control">
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
         <div class="row">
            <div class="col-sm-3 form-group">
            </div>
            <div class="col-sm-3 form-group">
               <img id="loading_result" src="https://media1.tenor.com/images/8ac12962c05648c55ca85771f4a69b2d/tenor.gif?itemid=9212724" style="height: 84px;" hidden="true">
            </div>
         </div>
      </div>
      <div id='myChart'><a class="zc-ref" href="https://www.zingchart.com/">Charts by ZingChart</a>
      </div>
   </body>
   <input type="hidden" id="uploaded_file_name" value="">
</html>
<!-- JS start -->
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<script type="text/javascript" src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/bootstrap-filestyle.min.js"></script> 
<script src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/pdf.js"></script>
<script>
   var base_url="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>";
</script>
<!-- <script src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/custom.js"></script>
 <!-- JS end -->
<script>
	$('#myFile').filestyle({
    buttonName: 'btn-success',
    buttonText: ' Open'
});

/*upload file data*/
function uploadFile() {
 	 
    var lang = $('#languagesInList :selected').val(); //$('#myFile').val();
    var file_data = $('#myFile').prop('files')[0];
    var apiKey=$('#myApiKey').val();
    if (lang == 0 || !file_data || !apiKey) {
        showErrorInput();
        return;
    }
    var file_size_valid=showFileSize();
	 if(!file_size_valid){
	 	showFileSizeError();
	 	return;
	 }
    filename = file_data['name'];
    file_ext = filename.split('.').pop();
    
    if ( !file_ext == 'pdf' || !file_ext == 'txt' ){
        showInvalidFileExt();
        return;
    }
    $('#loading_result').show();
    var form_data = new FormData();
    form_data.append('file', file_data);
    
    $.ajax({
        url: 'upload.php', // point to server-side PHP script 
        dataType: 'text', // what to expect back from the PHP script, if anything
        cache: false,
        async: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(php_script_response) {
            //  console.log(php_script_response); // display response from the PHP script, if any
            //document.getElementById("uploaded_file_name").val(php_script_response)
            $("#uploaded_file_name").val(php_script_response);
            if (file_ext == 'pdf') {
             
                getPdfContent();
            } else if (file_ext == 'txt') {
           
                getTxtContent()
            }

        }
    });

}

function getTxtContent() {
    var file = base_url + 'uploads/' + $("#uploaded_file_name").val();

    var rawFile = new XMLHttpRequest();
    rawFile.open("GET", file, true);
    rawFile.onreadystatechange = function() {
        if (rawFile.readyState === 4) {
            if (rawFile.status === 200 || rawFile.status == 0) {
                var allText = rawFile.responseText;
                console.log(allText);
                createJsonObjectforApi(allText);
            }
        }
    }
    rawFile.send(null);
}
/*json data*/
function createJsonObjectforApi(data) {
    var lang_chosen = $('#languagesInList :selected').val();
    //console.log(typeof data);
    //console.log(String(data));
    data = String(data);
    var dataToSend = {
        "texts": [{
            "body": data,
            "id": 1,
            "uri": "unique identifier 1"
        }],
        "language": lang_chosen,
        "includeSentences": true
    }
    dataToSend = JSON.stringify(dataToSend);
    callGavagaiApi(dataToSend)
}

$(document).ready(function() {
    console.log("ready!");
    var listItems = "<option > Select Language </option>";
    var jsonResult = ["aa", "bb", "cc"]; //provide default values
    for (var i = 0; i < jsonResult.length; i++) {
        listItems += "<option value='" + jsonResult[i] + "'>" + jsonResult[i] + "</option>";

    }
    $("#languagesInList").html(listItems);
    var jsonResult =["AR","AZ","BG","BN","CA","CS","DA","DE","EL","EN","ES","ET","FA","FI","FR","HE","HI","HR","HU","ID","IS","IT","JA","JV","KO","LT","LV","MS","NL","NO","PL","PT","RO","RU","SK","SL","SQ","SV","SW","TH","TL","TR","UK","UR","VI","ZH"];
    var listItems = "<option value=0> Select Language </option>";
            //var jsonResult =["aa","bb","cc"];
            for (var i = 0; i < jsonResult.length; i++) {
                listItems += "<option value='" + jsonResult[i] + "'>" + jsonResult[i] + "</option>";

            }
            $("#languagesInList").html(listItems);

    /*$.ajax({
        type: "GET",
        url: "https://api.gavagai.se/v3/languages?apiKey=757e4a593b564717c8a0201681febafd",
        success: function(jsonResult) {
            var listItems = "<option value=0> Select Language </option>";
            //var jsonResult =["aa","bb","cc"];
            for (var i = 0; i < jsonResult.length; i++) {
                listItems += "<option value='" + jsonResult[i] + "'>" + jsonResult[i] + "</option>";

            }
            $("#languagesInList").html(listItems);
        }
    });*/
});


function callGavagaiApi(dataToSend) {
	//loading_result
	$('#loading_result').show();
    var apiKey=$('#myApiKey').val();
    $.ajax({
        type: "POST",
        contentType: "application/json",
        dataType: "json",
        data: dataToSend,
        url: "https://api.gavagai.se/v3/tonality?apiKey="+apiKey,
        success: function(data) {
        	if(!data){
        		showGeneralError();
        		
        	}
            console.log(data);
            $('#loading_result').hide();
            //console.log(data.texts[0]);
            showPieChart(data.texts[0]);
        },
         error: function (jqXHR, exception) {
         	
         	showGeneralError();
         	$('#loading_result').hide();
         }
    });
}

function showPieChart(data) {
    
    for (var i = 0; i < data.tonality.length; i++) {
        //console.log(data.tonality[i]);

    }
    console.log(jsonObjectToShow);
    var jsonObjectToShow = [{
        values: [data.tonality[0]['normalizedScore']],
        text: data.tonality[0]['tone'],
        backgroundColor: '#50ADF5',
    }, {
        values: [data.tonality[1]['normalizedScore']],
        text: data.tonality[1]['tone'],
        backgroundColor: '#FF7965',
        detached: true
    }, {
        values: [data.tonality[2]['normalizedScore']],
        text: data.tonality[2]['tone'],
        backgroundColor: '#FFCB45',
        detached: true
    }, {
        values: [data.tonality[3]['normalizedScore']],
        text: data.tonality[3]['tone'],
        backgroundColor: '#cc0000'
    }, {
        text: data.tonality[4]['tone'],
        values: [data.tonality[4]['normalizedScore']],
        backgroundColor: '#ff66b3'
    }, {
        text: data.tonality[5]['tone'],
        values: [data.tonality[5]['normalizedScore']],
        backgroundColor: '#cc8800'
    }, {
        text: data.tonality[6]['tone'],
        values: [data.tonality[6]['normalizedScore']],
        backgroundColor: '#a300cc'
    }, {
        text: data.tonality[7]['tone'],
        values: [data.tonality[7]['normalizedScore']],
        backgroundColor: '#6FB07F'
    }];
    var myConfig = {
        type: "pie",
        plot: {
            borderColor: "#2B313B",
            borderWidth: 5,
            // slice: 90,
            valueBox: {
                placement: 'out',
                text: '%t\n%npv%',
                fontFamily: "Open Sans"
            },
            tooltip: {
                fontSize: '18',
                fontFamily: "Open Sans",
                padding: "5 10",
                text: "%npv%"
            },
            animation: {
                effect: 2,
                method: 5,
                speed: 900,
                sequence: 1,
                delay: 3000
            }
        },
        source: {
            text: 'gs.statcounter.com',
            fontColor: "#8e99a9",
            fontFamily: "Open Sans"
        },
        title: {
            fontColor: "#8e99a9",
            text: '',
            align: "centre",
            offsetX: 10,
            fontFamily: "Open Sans",
            fontSize: 25
        },
        subtitle: {
            offsetX: 10,
            offsetY: 10,
            fontColor: "#8e99a9",
            fontFamily: "Open Sans",
            fontSize: "16",
            text: '',
            align: "centre"
        },
        plotarea: {
            margin: "20 0 0 0"
        },
        series: jsonObjectToShow
    };

    zingchart.render({
        id: 'myChart',
        data: myConfig,
        height: '100%',
        width: '100%'
    });
}


function getPdfContent() {
  
    var urlPDF = base_url + 'uploads/' + $("#uploaded_file_name").val();
    //var urlPDF = '../gavagai/sample.pdf';
    PDFJS.workerSrc = base_url + 'js/pdf.worker.js';

    PDFJS.getDocument(urlPDF).then(function(pdf) {
        var pdfDocument = pdf;
        var pagesPromises = [];

        for (var i = 0; i < pdf.pdfInfo.numPages; i++) {
            // Required to prevent that i is always the total of pages
            (function(pageNumber) {
                pagesPromises.push(getPageText(pageNumber, pdfDocument));
            })(i + 1);
        }

        Promise.all(pagesPromises).then(function(pagesText) {

            createJsonObjectforApi(pagesText);
        });

    }, function(reason) {
        // PDF loading error
        console.error(reason);
        
    });

}
/**
 * Retrieves the text of a specif page within a PDF Document obtained through pdf.js 
 * 
 * @param {Integer} pageNum Specifies the number of the page 
 * @param {PDFDocument} PDFDocumentInstance The PDF document obtained 
 **/
function getPageText(pageNum, PDFDocumentInstance) {
    // Return a Promise that is solved once the text of the page is retrieven
    return new Promise(function(resolve, reject) {
        PDFDocumentInstance.getPage(pageNum).then(function(pdfPage) {
            // The main trick to obtain the text of the PDF page, use the getTextContent method
            pdfPage.getTextContent().then(function(textContent) {
                var textItems = textContent.items;
                var finalString = "";

                // Concatenate the string of the item to the final string
                for (var i = 0; i < textItems.length; i++) {
                    var item = textItems[i];

                    finalString += item.str + " ";
                }

                // Solve promise with the text retrieven from the page
                resolve(finalString);
            });
        });
    });
}

function showErrorInput() {
    //alert alert-warning
    $('#warning_notification').show()
    setTimeout(function() {
        $('#warning_notification').hide();
    }, 5000);
}

function showGeneralError() {
    //alert alert-warning
    $('#warning_notification_general').show()
    setTimeout(function() {
        $('#warning_notification_general').hide();
    }, 5000);
}
function showFileSizeError() {
    //alert alert-warning
    $('#warning_notification_file_size').show()
    setTimeout(function() {
        $('#warning_notification_file_size').hide();
    }, 5000);
}
function showInvalidFileExt() {
    //alert alert-warning
    $('#warning_notification_file_ext').show()
    setTimeout(function() {
        $('#warning_notification_file_ext').hide();
    }, 5000);
}
//showInvalidFileExt
function showFileSize() {
    var input, file;

    if (!window.FileReader) {
        alert("The file API isn't supported on this browser yet.");
        return;
    }

    input = document.getElementById('myFile');
    if (!input) {
        alert("Um, couldn't find the fileinput element.");
    }
    else if (!input.files) {
        alert("This browser doesn't seem to support the `files` property of file inputs.");
    }
    else if (!input.files[0]) {
        alert("Please select a file before clicking 'Load'");
    }
    else {
        file = input.files[0]; console.log(file);
        if(file.size < 2000000){
        	return true;
        }
        //bodyAppend("p", "File " + file.name + " is " + file.size + " bytes in size");
    }
    return false;
}
function bodyAppend(tagName, innerHTML) {
    var elm;

    elm = document.createElement(tagName);
    elm.innerHTML = innerHTML;
    document.body.appendChild(elm);
}
	</script>