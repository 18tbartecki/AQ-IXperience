var form = new FormData();
var settings = {
  "url": "http://api.airvisual.com/v2/countries&key=91269b01-77f3-4a6b-aea8-4e475c430aa5",
  "method": "GET",
  "timeout": 0,
  "processData": false,
  "mimeType": "multipart/form-data",
  "contentType": false,
  "data": form
};

$.ajax(settings).done(function (response) {
  console.log(response);
});