function searchAddress(key) {
  if(key.length > 1) {
    var form = new FormData();
    form.append('key', key);
    var request = new XMLHttpRequest();
    request.open('POST', '/classes/controllers/search_controller.php');
    request.send(form);
    request.onreadystatechange = function() {
      if(request.readyState == 4 && request.status == 200) {
        var response = JSON.parse(request.responseText);
        var list = '<ul class="list-group">';
        if(response != null) {
          for(var i=0; i<response.length; i++) {
            list += '<a href="#" class="list-group-item list-group-item-action" onclick="getAddress(this)">'+response[i].name+'</a>';
          }
        } else {
          list += '<a href="sites/property.php" class="list-group-item list-group-item-action">&emsp;Reference not found. Click here to add a new reference.&emsp;</a>';
        }
        list += '</ul>';
        document.getElementById('searchRes').innerHTML = list;
      }
    }
  }
  else
  {
    document.getElementById('searchRes').innerHTML = '';
  }
}

function getAddress(name)
{
  console.log(name.text);

  var str = name.text;
  document.getElementsByName('searchBox')[0].value = str;
  document.getElementById('searchRes').innerHTML = '';
}
