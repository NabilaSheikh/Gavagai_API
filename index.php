<!DOCTYPE html>
<html>
<head>
<?php $varhttp= "https://";?>
<link rel="stylesheet" type="text/css" href="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>css/main.css" /> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
.container{
	padding-top: 15px;
	width: 33%;
}
.btn-glyphicon {
    padding:8px;
    background:#ffffff;
    margin-right:4px;
}
.icon-btn {
    padding: 1px 15px 3px 2px;
    border-radius:50px;
}
</style>
</head>
<body>
	<div class="container">
		<div class="alert alert-warning" id="warning_notification" hidden>
		  <strong>Warning!</strong> All fields are compulsory !.
		</div>
	</div>
<div class="container">
	
	<div class="form-group">
		<input type="file" id="myFile" accept=".pdf,.txt">
	</div>
	<div class="form-group">
		<select class="form-control" id="languagesInList">
		</select>
	</div>
	<div class="form-group">
		<a class="btn icon-btn btn-info" onclick="uploadFile()" href="#">
		<span class="glyphicon btn-glyphicon glyphicon-share img-circle text-info"></span>
			Call API
		</a>
	</div>
</div>
		<div id='myChart'><a class="zc-ref" href="https://www.zingchart.com/">Charts by ZingChart</a>
		</div>
 	
 

</body>
</html>


<script type="text/javascript" src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/jquery-2.0.3.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<script type="text/javascript" src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/bootstrap-filestyle.min.js"></script> 
<script src="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/pdf.js"></script>

<input type="hidden" id="uploaded_file_name" value="">

<script>
	$('#myFile').filestyle({
				buttonName : 'btn-success',
                buttonText : ' Open'
			});
var base_url="<?php echo $varhttp . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>";

/*upload file data*/
function uploadFile() {
	var lang=$('#languagesInList :selected').val();//$('#myFile').val();
    var file_data = $('#myFile').prop('files')[0]; 
    if(lang == 0 || !file_data){
      showErrorInput();
      return;
      //alert('yeds');
	}
	//alert('no')
//return;
    filename =file_data['name'];
    file_ext= filename.split('.').pop();

    var form_data = new FormData();                  
    form_data.append('file', file_data);
    //alert(form_data);                             
    $.ajax({
                url: base_url +'upload.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
                  //  console.log(php_script_response); // display response from the PHP script, if any
                    //document.getElementById("uploaded_file_name").val(php_script_response)
                    $( "#uploaded_file_name" ).val( php_script_response );
                    if ( file_ext == 'pdf'){
                    	alert('pdf');
                    	//getPdfContent();
                    }else if(file_ext == 'txt'){
                    	alert('txt');
                    	//getTxtContent()
                    }
                    
                }
     });
    
}

function getTxtContent(){
	var file = base_url +'uploads/'+ $( "#uploaded_file_name" ).val();
        
	var rawFile = new XMLHttpRequest();
    rawFile.open("GET", file, false);
    rawFile.onreadystatechange = function ()
    {
        if(rawFile.readyState === 4)
        {
            if(rawFile.status === 200 || rawFile.status == 0)
            {
                var allText = rawFile.responseText;
                console.log(allText);
                createJsonObjectforApi(allText) ;
            }
        }
    }
    rawFile.send(null);
}
/*json data*/
function createJsonObjectforApi(data){
	var lang_chosen=$('#languagesInList :selected').val();
	//console.log(typeof data);
	//console.log(String(data));
	data=String(data);
	var dataToSend =
{  
   "texts":[  
      {  
         "body":data,
	  "id":1,
         "uri":"unique identifier 1"
      }
   ],
   "language":lang_chosen,
    "includeSentences": true
}
dataToSend = JSON.stringify(dataToSend);
callGavagaiApi(dataToSend)
}

$( document ).ready(function() {
    console.log( "ready!" );
    var listItems ="<option > Select Language </option>";
    var jsonResult =["aa","bb","cc"];
    for (var i = 0; i < jsonResult.length; i++) {
    	listItems += "<option value='" + jsonResult[i] + "'>" + jsonResult[i] + "</option>";

    }
    $("#languagesInList").html(listItems);
    
    $.ajax({
			type: "GET",
	        url: "https://api.gavagai.se/v3/languages?apiKey=084e65c223f1235947e07676e7757b5d",
	        success: function (jsonResult) {
	                    var listItems ="<option value=0> Select Language </option>";
					    //var jsonResult =["aa","bb","cc"];
					    for (var i = 0; i < jsonResult.length; i++) {
					    	listItems += "<option value='" + jsonResult[i] + "'>" + jsonResult[i] + "</option>";

					    }
					    $("#languagesInList").html(listItems);
	                }
	});   
});


function callGavagaiApi(dataToSend){
	//console.log(dataToSend);
	$.ajax({
			type: "POST",
   			contentType: "application/json",
   			dataType: "json",
   			data:dataToSend,
	        url: "https://api.gavagai.se/v3/tonality?apiKey=084e65c223f1235947e07676e7757b5d",
	        success: function (data) {
	                  console.log(data);
	                  //console.log(data.texts[0]);
	                  showPieChart(data.texts[0]);
	                }
	});                  
}

function showPieChart(data){
	//alert('ok');
	for (var i = 0; i < data.tonality.length; i++) { 
    //console.log(data.tonality[i]);

}
 console.log(jsonObjectToShow);
	var jsonObjectToShow= [{
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
        text: 'Tonality Results',
        align: "left",
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
        text: '-By Gavagai',
        align: "left"
      },
      plotarea: {
        margin: "20 0 0 0"
      },
      series:jsonObjectToShow
    };

    zingchart.render({
      id: 'myChart',
      data: myConfig,
      height: '100%',
      width: '100%'
    });
}

     
	function getPdfContent(){
        var urlPDF = base_url +'uploads/'+ $( "#uploaded_file_name" ).val();
        //var urlPDF = '../gavagai/sample.pdf';
        PDFJS.workerSrc =  base_url +'js/pdf.worker.js';

        PDFJS.getDocument(urlPDF).then(function (pdf) {
            var pdfDocument = pdf;
            var pagesPromises = [];

            for (var i = 0; i < pdf.pdfInfo.numPages; i++) {
                // Required to prevent that i is always the total of pages
                (function (pageNumber) {
                    pagesPromises.push(getPageText(pageNumber, pdfDocument));
                })(i + 1);
            }

            Promise.all(pagesPromises).then(function (pagesText) {

                // Display text of all the pages in the console
                //console.log(pagesText);
                //alert(pagesText);
                createJsonObjectforApi(pagesText) ;
            });

        }, function (reason) {
            // PDF loading error
            console.error(reason);
            //alert(reason);
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
            return new Promise(function (resolve, reject) {
                PDFDocumentInstance.getPage(pageNum).then(function (pdfPage) {
                    // The main trick to obtain the text of the PDF page, use the getTextContent method
                    pdfPage.getTextContent().then(function (textContent) {
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
        function showErrorInput(){
	         //alert alert-warning
	         $('#warning_notification').show()
	         setTimeout(function(){ $('#warning_notification').hide(); }, 5000);
	     }
    </script>