var store = {
  type: null,
  init: function(type){
    store.type = type;
    $('body').on(store.actions);
  },
  process: function(result){
    if (result.finished) {
      document.location.href = document.location.href;
    } else {
      console.log(result.percent+'% - '+result.processed);
      $.post('/store/ajax/put/'+store.type+'/process', function(resultData){
        store.process(resultData);
      }, 'json');
    }
  },
  actions: {
    putAll: function(e, data){
      $.post('/store/ajax/put/'+store.type+'/all', function(resultData){
        store.process('putAll', e, data, resultData);
      }, 'json');
    },
    putChecked: function(e, data){
      $.post('/store/ajax/put/'+store.type+'/checked', {
        items: $('.citem:checked').map(function(){
          return $(this).val();
        }).get()
      }, function(resultData){
        store.process('putChecked', e, data, resultData);
      }, 'json');
    },
  }
}