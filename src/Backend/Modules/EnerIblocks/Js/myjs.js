$(window).load(function() { 

    
// {"title":"Person", "type":"object","properties":{"name":{"type":"string","description":"First and Last name","minLength":4,"default":"Jeremy Dorn"}}}
// if($('.tree') != undefined){
//   $('.tree').treegrid({
//     'initialState': 'collapsed',
//     'saveState': false,
//   });
// }

  var meta = document.getElementById('editor_meta');
  let meta_data = document.getElementById('meta_data').value;
  meta_data = JSON.parse(meta_data);
  // console.log(meta_data);

  let text_require = '<abbr data-toggle="tooltip" aria-label="Обязательное поле" title="" data-original-title="Обязательное поле">*</abbr>';
  meta_data.forEach(element => {
    console.log(element.id);
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;

    
    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);
    label_id.innerHTML = element.title + ' ' + text_require;

    
    
    id = document.createElement('input'); 
    id.setAttribute('type', element.type);
    id.setAttribute('name', element.code);
    id.setAttribute('class', 'form-control');
    id.setAttribute('value', element.title);
    // id.setAttribute('required', 'required');
    console.log(id);
    
    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');
    div_id.appendChild(label_id);
    div_id.appendChild(id);

    meta.appendChild(div_id);
  });

  // https://stackoverflow.com/questions/18640051/check-if-html-form-values-are-empty-using-javascript
/*function checkform(form) {
    // get all the inputs within the submitted form
    var inputs = form.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        // only validate the inputs that have the required attribute
        if(inputs[i].hasAttribute("required")){
            if(inputs[i].value == ""){
                // found an empty field that is required
                alert("Please fill all required fields");
                return false;
            }
        }
    }
    return true;
}*/
});