Skip to content
This repository
Search
Pull requests
Issues
Marketplace
Explore
 @NabilaSheikh
 Sign out
 Unwatch 1
  Star 0  Fork 0 NabilaSheikh/Gavagai_API
 Code  Issues 0  Pull requests 0  Projects 0  Wiki  Insights  Settings
Tree: 548694706f Find file Copy pathGavagai_API/index.php
5486947  14 hours ago
@NabilaSheikh NabilaSheikh ok
1 contributor
RawBlameHistory      
297 lines (271 sloc)  8.75 KB
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>css/main.css" /> 
 
</head>
<body>


<input type="file" id="myFile" accept=".docx,.pdf,.txt">
<button class="button" onclick="uploadFile()">Call API</button>

<div id='myChart'><a class="zc-ref" href="https://www.zingchart.com/">Charts by ZingChart</a></div>
 

</body>
</html>


<script type="text/javascript" src="<?php echo "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/jquery-2.0.3.min.js"></script> 
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<input type="hidden" id="uploaded_file_name" value="">

<script>
  var base_url="<?php echo "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>";
/*upload file data*/
function uploadFile() {
    var file_data = $('#myFile').prop('files')[0]; 
    if(file_data){ 
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    //alert(form_data);                             
    $.ajax({
                url: 'upload.php', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
                    console.log(php_script_response); // display response from the PHP script, if any
                    //document.getElementById("uploaded_file_name").val(php_script_response)
                    $( "#uploaded_file_name" ).val( php_script_response );
                    getPdfContent();
                }
     });
    }
}
/*json data*/
function createJsonObjectforApi(data){
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
   "language":"EN",
    "includeSentences": true
}
dataToSend = JSON.stringify(dataToSend);
callGavagaiApi(dataToSend)
}
/*var dataToSend =
{  
   "texts":[  
      {  
         "body":"love text to analyze",
    "id":1,
         "uri":"unique identifier 1"
      }
   ],
   "language":"EN",
    "includeSentences": true
}
dataToSend = JSON.stringify(dataToSend);*/
 ;
/*$('input[type=file]').change(function () {
    console.log(this.files[0].mozFullPath);
    var x = document.getElementById("myFile").value;
  alert(x);
});*/
function callGavagaiLanguageApi(){
  /*$.ajax({
      type: "GET",
          url: "https://api.gavagai.se/v3/languages?apiKey=2d2afd9daf4cc5a17409b92f4c1a0c7e",
          success: function (data) {
                      alert(data);
                  }
  });  */                
}
function callGavagaiApi(dataToSend){
  //console.log(dataToSend);
  $.ajax({
      type: "POST",
        contentType: "application/json",
        dataType: "json",
        data:dataToSend,
          url: "https://api.gavagai.se/v3/tonality?apiKey=2d2afd9daf4cc5a17409b92f4c1a0c7e",
          success: function (data) {
                    console.log(data);
                    //console.log(data.texts[0]);
                    showPieChart(data.texts[0]);
                  }
  });                  
}
function showPieChart(data){
  alert('ok');
  for (var i = 0; i < data.tonality.length; i++) { 
    console.log(data.tonality[i]);
    /*if(data.tonality[i]['normalizedScore']){
      alert(data.tonality[i]['normalizedScore']);
      var jsonObjectToShow =[
        {i:[
          //values:data.tonality[i]['normalizedScore'],
          //text:data.tonality[i]['tone']
        ]
      }
      ]
    }*/
    /*jsonObjectToShow[]={
      values:data.tonality[i]['normalizedScore']}
    }*/
    //console.log(jsonObjectToShow);
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
        backgroundColor: '#6877e5'
      }, {
        text: data.tonality[4]['tone'],
        values: [data.tonality[4]['normalizedScore']],
        backgroundColor: '#6FB07F'
      }, {
        text: data.tonality[5]['tone'],
        values: [data.tonality[5]['normalizedScore']],
        backgroundColor: '#6FB07F'
      }, {
        text: data.tonality[6]['tone'],
        values: [data.tonality[6]['normalizedScore']],
        backgroundColor: '#6FB07F'
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
        text: 'Global Browser Usage',
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
        text: 'May 2016',
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
                </script>
<script src="<?php echo "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>js/pdf.js"></script>
<script>
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
    </script>