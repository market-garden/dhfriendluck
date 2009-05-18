	  function isFriend() {
          // var withfriendid = document.getElementById('person').value;
		   //alert(withfriendid);
		   var req = opensocial.newDataRequest();
           //var isFriendspec = opensocial.newIdSpec({ "userId" : "OWNER","groupId" : "FRIENDS" });
           var opt_params = {};
           opt_params[opensocial.DataRequest.PeopleRequestFields.MAX] = 200;
           //opt_params[opensocial.DataRequest.FilterType.IS_FRIENDS_WITH] = withfriendid;
           opt_params[opensocial.DataRequest.PeopleRequestFields.FILTER] =opensocial.DataRequest.FilterType.HAS_APP;
      	   //opt_params[opensocial.DataRequest.PeopleRequestFields.FILTER] =opensocial.DataRequest.FilterType.IS_FRIENDS_WITH;
		   req.add(req.newFetchPeopleRequest(opensocial.DataRequest.Group.OWNER_FRIENDS, opt_params), 'isfr');
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

     