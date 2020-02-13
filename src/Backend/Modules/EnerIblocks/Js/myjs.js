var BuilderFormMeta = function() { 

  var insertLabelRequired = function(){
    return '<abbr data-toggle="tooltip" aria-label="Обязательное поле" title="" data-original-title="Обязательное поле">*</abbr>';
  }

  var checkboxUpdate = function(){
    // TODO: сделаю пока на jQuery, но перепишу на JS
    $(".fork-form-checkbox").click(function(){
      console.log($(this).is(':checked'));
      if($(this).is(':checked')){
        $(this).val('1');
      }else{
        $(this).attr('checked',false);
        $(this).val('0');
      }
    });

    // $(".fork-form-checkbox").click(function(){
    //   var inputs = document.querySelectorAll("input[type='checkbox']");
    //   for(var i = 0; i < inputs.length; i++) {
    //     console.log('start');
    //     if(inputs[i].checked = true){
    //       inputs[i].value = 1;
    //     }else{
    //       inputs[i].value = '';
    //     }
    //   }
    // });

  }

  var checkbox = function(element){
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;
    let label_id_code = 'label_code_'+element.id;

    id = document.createElement('input'); 
    id.setAttribute('type', element.type);
    id.setAttribute('name', element.code);
    id.setAttribute('class', 'fork-form-checkbox');
    if (element.value == 1) {
      id.setAttribute('value', element.value);
      id.setAttribute('checked', true);
    }

    if (element.required == 1) {
      id.setAttribute('required', true);
    }

    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');

    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);

    label_id_code = document.createElement('label');
    label_id_code.innerHTML = element.code
    label_id_code.setAttribute('style', 'float: right; font-size: 10px; color: gray;');

    if (element.required == 1) {
      label_id.innerHTML = element.title + ' ' + insertLabelRequired();
    }else{
      label_id.innerHTML = element.title + ' &nbsp;'
    }
    div_id.appendChild(label_id);
    div_id.appendChild(label_id_code);
    div_id.appendChild(id);

    document.getElementById('editor_meta').appendChild(div_id);
    return div_id;
  }

  var image = function(element){
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;
    let label_id_code = 'label_code_'+element.id;

    id = document.createElement('input'); 
    id.setAttribute('type', 'text');
    id.setAttribute('name', element.code);
    id.setAttribute('class', 'form-control mediaselect');
    if (element.value) {
      id.setAttribute('value', element.value);
    }

    if (element.required == 1) {
      id.setAttribute('required', true);
    }

    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');

    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);

    label_id_code = document.createElement('label');
    label_id_code.innerHTML = element.code
    label_id_code.setAttribute('style', 'float: right; font-size: 10px; color: gray;');

    if (element.required == 1) {
      label_id.innerHTML = element.title + ' ' + insertLabelRequired();
    }else{
      label_id.innerHTML = element.title
    }
    div_id.appendChild(label_id);
    div_id.appendChild(label_id_code);
    div_id.appendChild(id);

    document.getElementById('editor_meta').appendChild(div_id);
  }

  var textarea = function(element){
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;
    let label_id_code = 'label_code_'+element.id;

    id = document.createElement("TEXTAREA");
    id.setAttribute('name', element.code);
    id.setAttribute('maxlength', 1000);
    id.setAttribute('class', 'form-control');
    id.setAttribute('rows', 5);
    if (element.value) {
      id.innerText = element.value;
    }

    if (element.required == 1) {
      id.setAttribute('required', true);
    }
    
    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');

    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);

    label_id_code = document.createElement('label');
    label_id_code.innerHTML = element.code
    label_id_code.setAttribute('style', 'float: right; font-size: 10px; color: gray;');

    if (element.required == 1) {
      label_id.innerHTML = element.title + ' ' + insertLabelRequired();
    }else{
      label_id.innerHTML = element.title
    }
    div_id.appendChild(label_id);
    div_id.appendChild(label_id_code);
    div_id.appendChild(id);

    document.getElementById('editor_meta').appendChild(div_id);
  }

  var select = function(element){
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;
    let label_id_code = 'label_code_'+element.id;

    id = document.createElement('select'); 
    id.setAttribute('type', element.type);
    id.setAttribute('name', element.code);
    id.setAttribute('class', 'form-control');
    if (element.value) {
      id.setAttribute('value', element.value);
    }

    if (element.required == 1) {
      id.setAttribute('required', true);
    }

    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');

    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);

    label_id_code = document.createElement('label');
    label_id_code.innerHTML = element.code
    label_id_code.setAttribute('style', 'float: right; font-size: 10px; color: gray;');

    if (element.required == 1) {
      label_id.innerHTML = element.title + ' ' + insertLabelRequired();
    }else{
      label_id.innerHTML = element.title
    }

    console.log(element);

    //создаю по умолчанию
    var option = document.createElement("option");
        option.setAttribute("value",'');
        option.disabled = 'true';
        if (element.value == '') {
          option.selected = 'selected';
        }
        option.innerHTML = 'Выбрать из списка';
        id.appendChild(option);

    if (element.list != undefined) {
        element.list.forEach((item)=>{
          console.log(item);
            var option = document.createElement("option");
            option.setAttribute("value", item.key);
            if (element.value == item.key) {
              option.selected = 'selected';
            }
            option.innerHTML = item.value;
            id.appendChild(option);
        });
    }

    div_id.appendChild(label_id);
    div_id.appendChild(label_id_code);
    div_id.appendChild(id);

    document.getElementById('editor_meta').appendChild(div_id);
  }

  var multiselect = function(element){
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;
    let label_id_code = 'label_code_'+element.id;

    id = document.createElement('select'); 
    id.setAttribute('type', 'select');
    id.setAttribute('name', element.code);
    id.setAttribute('class', 'form-control js-example-basic-multiple-limit"');
    if (element.value) {
      id.setAttribute('value', element.value);
    }

    if (element.required == 1) {
      id.setAttribute('required', true);
    }

    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');

    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);

    label_id_code = document.createElement('label');
    label_id_code.innerHTML = element.code
    label_id_code.setAttribute('style', 'float: right; font-size: 10px; color: gray;');

    if (element.required == 1) {
      label_id.innerHTML = element.title + ' ' + insertLabelRequired();
    }else{
      label_id.innerHTML = element.title
    }

    console.log(element);

    //создаю по умолчанию
    var option = document.createElement("option");
        option.setAttribute("value",'');
        option.disabled = 'true';
        if (element.value == '') {
          option.selected = 'selected';
        }
        option.innerHTML = 'Выбрать из списка';
        id.appendChild(option);

    if (element.list != undefined) {
        element.list.forEach((item)=>{
          console.log(item);
            var option = document.createElement("option");
            option.setAttribute("value", item.key);
            if (element.value == item.key) {
              option.selected = 'selected';
            }
            option.innerHTML = item.value;
            id.appendChild(option);
        });
    }

    div_id.appendChild(label_id);
    div_id.appendChild(label_id_code);
    div_id.appendChild(id);

    document.getElementById('editor_meta').appendChild(div_id);
  }

  var number = function(element){
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;
    let label_id_code = 'label_code_'+element.id;

    id = document.createElement('input'); 
    id.setAttribute('type', element.type);
    id.setAttribute('name', element.code);
    id.setAttribute('class', 'form-control');
    if (element.value) {
      id.setAttribute('value', element.value);
    }

    if (element.required == 1) {
      id.setAttribute('required', true);
    }

    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');

    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);

    label_id_code = document.createElement('label');
    label_id_code.innerHTML = element.code
    label_id_code.setAttribute('style', 'float: right; font-size: 10px; color: gray;');

    if (element.required == 1) {
      label_id.innerHTML = element.title + ' ' + insertLabelRequired();
    }else{
      label_id.innerHTML = element.title
    }
    div_id.appendChild(label_id);
    div_id.appendChild(label_id_code);
    div_id.appendChild(id);

    document.getElementById('editor_meta').appendChild(div_id);
  }

  var radio = function(element){
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;
    let label_id_code = 'label_code_'+element.id;

    id = document.createElement('input'); 
    id.setAttribute('type', element.type);
    id.setAttribute('name', element.code);
    id.setAttribute('class', 'fork-form-radio');
    if (element.value) {
      id.setAttribute('value', element.value);
    }

    if (element.required == 1) {
      id.setAttribute('required', true);
    }

    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');

    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);

    label_id_code = document.createElement('label');
    label_id_code.innerHTML = element.code
    label_id_code.setAttribute('style', 'float: right; font-size: 10px; color: gray;');

    if (element.required == 1) {
      label_id.innerHTML = element.title + ' ' + insertLabelRequired();
    }else{
      label_id.innerHTML = element.title
    }
    div_id.appendChild(label_id);
    div_id.appendChild(label_id_code);
    div_id.appendChild(id);

    document.getElementById('editor_meta').appendChild(div_id);
  }

  var string = function(element){
    let id = element.id;
    let div_id = 'div_'+element.id;
    let label_id = 'label_'+element.id;
    let label_id_code = 'label_code_'+element.id;

    id = document.createElement('input'); 
    id.setAttribute('type', 'text');
    id.setAttribute('name', element.code);
    id.setAttribute('class', 'form-control');
    if (element.value) {
      id.setAttribute('value', element.value);
    }

    if (element.required == 1) {
      id.setAttribute('required', true);
    }
    
    // id.setAttribute('required', 'required');
    // console.log(id);
    
    div_id = document.createElement('div'); 
    div_id.setAttribute('class', 'form-group');

    label_id = document.createElement('label');
    label_id.setAttribute('for', element.title);

    label_id_code = document.createElement('label');
    label_id_code.innerHTML = element.code
    label_id_code.setAttribute('style', 'float: right; font-size: 10px; color: gray;');

    if (element.required == 1) {
      label_id.innerHTML = element.title + ' ' + insertLabelRequired();
    }else{
      label_id.innerHTML = element.title
    }
    div_id.appendChild(label_id);
    div_id.appendChild(label_id_code);
    div_id.appendChild(id);

    document.getElementById('editor_meta').appendChild(div_id);
  }

  var includeJS = function(link){
    var head= document.getElementsByTagName('head')[0];
    var script= document.createElement('script');
    script.type= 'text/javascript';
    script.src= link;
    head.appendChild(script);
  }

  var includeCSS = function(link){
    var head= document.getElementsByTagName('head')[0];
    var script= document.createElement('link');
    script.rel= 'stylesheet';
    script.href= link;
    head.appendChild(script);
  }

  var initializeSelect = function(){
    $('select').select2({
      placeholder: 'This is my placeholder',
    });

    $(".js-example-basic-multiple-limit").select2({
      maximumSelectionLength: 100000
    });
  }

  var start = function(){
    let meta_data = document.getElementById('meta_data').value;
    meta_data = JSON.parse(meta_data);
    // console.log(meta_data);

    /*
    let type_obj = {
      'string' : string(element),
      'number' : number(element),
      'radio' : radio(element),
      'textarea' : textarea(element),
      'image' : image(element),
      'checkbox' : checkbox(element),
    }
    */

    var select_js = 0;
    meta_data.forEach(element => {
      // console.log(element.id);
      // console.log(element);
      // let f = type_obj[element.code];
      // f(element);

        //TODO: поработать над условием
        if (element.type == 'string') {
          string(element);
        }

        if (element.type == 'radio') {
          radio(element);
        }

        if (element.type == 'number') {
          number(element);
        }

        if (element.type == 'textarea') {
          textarea(element);
        }

        if (element.type == 'image') {
          image(element);
        }

        if (element.type == 'checkbox') {
          checkbox(element);
        }

        if (element.type == 'select') {
          select(element);
        }

        if (element.type == 'multiselect') {
          if(select_js == 0){
            includeCSS('https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css');
            includeJS('https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js');
          }

          select_js++;
          multiselect(element);

          setTimeout(initializeSelect(), 3000);
          
        }


    });

    initializeSelect();

    $('#editor_meta .mediaselect').each(function () {
      addMediaBrowser(this);
    });
  }


  return {
      init: function() {
          start();
          checkboxUpdate();
      }
  };

}();



$(window).load(function() { 

  BuilderFormMeta.init();





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

