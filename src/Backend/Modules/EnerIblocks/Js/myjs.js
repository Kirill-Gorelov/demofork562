$(window).load(function() { 

    
// {"title":"Person", "type":"object","properties":{"name":{"type":"string","description":"First and Last name","minLength":4,"default":"Jeremy Dorn"}}}
$('.tree').treegrid({
  'initialState': 'collapsed',
  'saveState': false,
});


var f = document.getElementById('editor');
var i = document.createElement("input"); //input element, text
i.setAttribute('type',"text");
i.setAttribute('name',"username");

var s = document.createElement("input"); //input element, Submit button
s.setAttribute('type',"submit");
s.setAttribute('value',"Submit");

f.appendChild(i);
f.appendChild(s);

});