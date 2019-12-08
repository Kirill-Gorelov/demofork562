$(window).load(function() { 

  document.addEventListener('keyup', function(event) {
    if (document.getElementById('title') && document.getElementById('code')) {
      document.getElementById('code').value = rus_to_latin(document.getElementById('title').value);
    }

    if (document.getElementById('category_title') && document.getElementById('category_code')) {
      document.getElementById('category_code').value = rus_to_latin(document.getElementById('category_title').value);
    }
  });


function rus_to_latin ( str ) {
    
  var ru = {
      'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 
      'е': 'e', 'ё': 'e', 'ж': 'j', 'з': 'z', 'и': 'i', 
      'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 
      'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 
      'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch', 'ш': 'sh', 
      'щ': 'shch', 'ы': 'y', 'э': 'e', 'ю': 'u', 'я': 'ya',
      ' ':'_',',':'', '.':'', ':':'', ':':'', '?':'', '*':'', '<':'', '>':''
  }, n_str = [];
  
  str = str.replace(/[ъь]+/g, '').replace(/й/g, 'i');
  
  for ( var i = 0; i < str.length; ++i ) {
     n_str.push(
            ru[ str[i] ]
         || ru[ str[i].toLowerCase() ] == undefined && str[i]
         || ru[ str[i].toLowerCase() ].replace(/^(.)/, function ( match ) { return match.toLowerCase() })
     );
  }
  
  return n_str.join('');
}


});

