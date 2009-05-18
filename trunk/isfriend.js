	  function isFriend() {
           var withfriendid = document.getElementById('person').value;
		   alert(withfriendid);
		   var req = opensocial.newDataRequest();
           var isFriendspec = opensocial.newIdSpec({ "userId" : "VIEWER","groupId" : "FRIENDS" });
           var opt_params = {};
           opt_params[opensocial.DataRequest.PeopleRequestFields.MAX] = 200;
           opt_params[opensocial.DataRequest.FilterType.IS_FRIENDS_WITH] = withfriendid;
      	   req.add(req.newFetchPeopleRequest(isFriendspec, opt_params), 'isfr');
           req.send(onLoadFriends);
}


        function onLoadFriends(data) {
         
          var isFriends = data.get('isfr').getData();

          html = new Array();
          html.push('<ul>');
          isFriends.each(function(person) {
            if (person.getId()) {
              html.push('<li>', person.getDisplayName(), '</li>');
            }
          });
          html.push('</ul>');
          document.getElementById('info').innerHTML = html.join('');
         
        }

     