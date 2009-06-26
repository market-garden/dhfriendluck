    function getpersonmore(pid) {
          //var vf = data.get('f').getData();

		  if (pid == 1)
		  {
		  var vf = owner;
		  }
		  else{
		  var friend = document.getElementById('person').value;
          var vf = ownerFriends.getById(friend);
		  }
          			   
			     var html = "";
          
		
		       html += '<img src='+vf.getField(opensocial.Person.Field.THUMBNAIL_URL)+'>'
           html += "<br>";
			   		  						
													
           var displayName = vf.getDisplayName();
           html += "姓名: "+displayName;
           html += "<br>";	
			   
           var ID = vf.getId();
			     html += "ID: "+ID;
           html += "<br>";	
		 
		        var gender = vf.getField(opensocial.Person.Field.GENDER);
		        if(gender){
		          html += "gender: "+gender.getDisplayValue();
		          html += "<br>";
            };
    
    		    var HeadUrl = vf.getField('headUrl');
            html += "中头像: "+HeadUrl;
            html += "<br>";  
           
            var MainUrl = vf.getField('mianUrl');
            html += "大头像: "+MainUrl;
            html += "<br>";  
            
            var Birthday = vf.getField('birthday');
            html += "生日: "+Birthday;
            html += "<br>";          
            
            var Star = vf.getField('star');
            html += "星级: "+Star;
            html += "<br>";   
        
		  
      
	      var addresses = vf.getField(opensocial.Person.Field.ADDRESSES);
          html += "addresses: "+addresses;
          html += "<br>";
          
	            var Organizations = vf.getField('organizations');
            html += "组织: "+Organizations;
            html += "<br>"; 	  
		  
		      var Highschool = vf.getField('highSchool');
          html += "高中: "+Highschool;
          html += "<br>";

   
		  var University = vf.getField('university');
		  html += "大学: "+University;
		  html += "<br>"



   
		  var dateOfBirth = vf.getField(opensocial.Person.Field.DATE_OF_BIRTH);
		  html += "dateOfBirth: "+dateOfBirth;
		  html += "<br>";




   
		  var hasApp = vf.getField(opensocial.Person.HAS_APP);
		  html += "hasApp: "+hasApp;
		  html += "<br>";



		  var networkPresence = vf.getField(opensocial.Person.Field.NETWORK_PRESENCE);
		  html += "networkPresence: "+networkPresence;
		  html += "<br>";

		document.getElementById('info').innerHTML = html;		
        gadgets.window.adjustHeight();
		};
        