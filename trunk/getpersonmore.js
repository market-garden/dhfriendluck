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
			   
		       html +=  vf.getField(opensocial.Person.Field.PROFILE_URL)+""
               html += "<br>";			   
											
	           var isOwner = vf.isOwner();
               html += "isOwner: "+isOwner;
               html += "<br>";

               var isViewer = vf.isViewer();
               html += "isViewer: "+isViewer;
               html += "<br>";							
													
               var displayName = vf.getDisplayName();
               html += "Name: "+displayName;
               html += "<br>";	
			   
               var ID = vf.getId();
			   html += "ID: "+ID;
               html += "<br>";	


           var nickName = vf.getField(opensocial.Person.Field.NICKNAME); 
           html += "nickName: "+nickName;
           html += "<br>";

          
		   
		   
		  var currentLocation = vf.getField(opensocial.Person.Field.CURRENT_LOCATION); 
           if(currentLocation!=undefined){
           html += "Country: "+currentLocation.getField(opensocial.Address.Field.COUNTRY);
		   html += "<br>";
		   html += "Provice: "+currentLocation.getField(opensocial.Address.Field.REGION);
		   html += "<br>";
		   html += "City: "+currentLocation.getField(opensocial.Address.Field.LOCALITY);
		   html += "<br>";}
		   else{
           html += "Country: "+currentLocation;
		   html += "<br>";
		   html += "Provice: "+currentLocation;
		   html += "<br>";
		   html += "City: "+currentLocation;
		   html += "<br>";}
		  
      
	      var addresses = vf.getField(opensocial.Person.Field.ADDRESSES);
          html += "addresses: "+addresses;
          html += "<br>";
          
		  
		  var emails = vf.getField(opensocial.Person.Field.EMAILS);
          html += "emails: "+emails;
          html += "<br>";

   
		  var phoneNumbers = vf.getField(opensocial.Person.Field.PHONE_NUMBERS);
		  html += "phoneNumbers: "+phoneNumbers;
		  html += "<br>"



		  var aboutMe = vf.getField(opensocial.Person.Field.ABOUT_ME);
		  html += "aboutMe: "+aboutMe;
		  html += "<br>";

		     var status = vf.getField(opensocial.Person.Field.STATUS); 
          html += "status: "+status;
          html += "<br>";
      
	      var profileSong = vf.getField(opensocial.Person.Field.PROFILE_SONG);
          html += "profileSong: "+profileSong;
          html += "<br>";
          
		  
		  var profileVideo = vf.getField(opensocial.Person.Field.PROFILE_VIDEO);
          html += "profileVideo: "+profileVideo;
          html += "<br>";

   
		  var gender = vf.getField(opensocial.Person.Field.GENDER);
		  if(gender){
		  html += "gender: "+gender.getDisplayValue();
		  html += "<br>";
          };

		  var sexualOrientation = vf.getField(opensocial.Person.Field.SEXUAL_ORIENTATION);
		  html += "sexualOrientation: "+sexualOrientation;
		  html += "<br>";

		  var relationshipStatus = vf.getField(opensocial.Person.Field.RELATIONSHIP_STATUS);
          html += "relationshipStatus: "+relationshipStatus;
          html += "<br>";
          
		  
		  var age = vf.getField(opensocial.Person.Field.AGE);
          html += "age: "+age;
          html += "<br>";

   
		  var dateOfBirth = vf.getField(opensocial.Person.Field.DATE_OF_BIRTH);
		  html += "dateOfBirth: "+dateOfBirth;
		  html += "<br>";



		  var bodyType = vf.getField(opensocial.Person.Field.BODY_TYPE);
		  html += "bodyType: "+bodyType;
		  html += "<br>";

			//  
			
		  var ethnicity = vf.getField(opensocial.Person.Field.ETHNICITY);
		  html += "ethnicity: "+ethnicity;
		  html += "<br>";



		  var smoker = vf.getField(opensocial.Person.Field.SMOKER);
		  html += "smoker: "+smoker;
		  html += "<br>";

		  var drinker = vf.getField(opensocial.Person.Field.DRINKER);
          html += "drinker: "+drinker;
          html += "<br>";
          
		  
		  var children = vf.getField(opensocial.Person.Field.CHILDREN);
          html += "children: "+children;
          html += "<br>";

   
		  var pets = vf.getField(opensocial.Person.PETS);
		  html += "pets: "+pets;
		  html += "<br>";



		  var livingArrangement = vf.getField(opensocial.Person.Field.LIVING_ARRANGEMENT);
		  html += "livingArrangement: "+livingArrangement;
		  html += "<br>";

		  //
		  var timeZone = vf.getField(opensocial.Person.Field.TIME_ZONE);
		  html += "timeZone: "+timeZone;
		  html += "<br>";



		  var languagesSpoken = vf.getField(opensocial.Person.Field.LANGUAGES_SPOKEN);
		  html += "languagesSpoken: "+languagesSpoken;
		  html += "<br>";

		  	      var jobs = vf.getField(opensocial.Person.Field.JOBS);
          html += "jobs: "+jobs;
          html += "<br>";
          
		  
		  var jobInterests = vf.getField(opensocial.Person.Field.JOB_INTERESTS);
          html += "jobInterests: "+jobInterests;
          html += "<br>";

   
		  var schools = vf.getField(opensocial.Person.SCHOOLS);
		  html += "schools: "+schools;
		  html += "<br>";



		  var interests = vf.getField(opensocial.Person.Field.INTERESTS);
		  html += "interests: "+interests;
		  html += "<br>";
//
		  var urls = vf.getField(opensocial.Person.Field.URLS);
		  html += "urls: "+urls;
		  html += "<br>";



		  var music = vf.getField(opensocial.Person.Field.MUSIC);
		  html += "music: "+music;
		  html += "<br>";

		  var movies = vf.getField(opensocial.Person.Field.MOVIES);
		  html += "movies: "+movies;
		  html += "<br>";

		  	   
				  
				  var tvShows = vf.getField(opensocial.Person.Field.TV_SHOWS);
          html += "tvShows: "+tvShows;
          html += "<br>";
          
		  
		  var books = vf.getField(opensocial.Person.Field.BOOKS);
          html += "books: "+books;
          html += "<br>";

   
		  var activities = vf.getField(opensocial.Person.ACTIVITIES);
		  html += "activities: "+activities;
		  html += "<br>";



		  var sports = vf.getField(opensocial.Person.Field.SPORTS);
		  html += "sports: "+sports;
		  html += "<br>";
		  //
		  var heroes = vf.getField(opensocial.Person.Field.HEROES);
		  html += "heroes: "+heroes;
		  html += "<br>";



		  var quotes = vf.getField(opensocial.Person.Field.QUOTES);
		  html += "quotes: "+quotes;
		  html += "<br>";

		  	      var cars = vf.getField(opensocial.Person.Field.CARS);
          html += "cars: "+cars;
          html += "<br>";
          
		  
		  var food = vf.getField(opensocial.Person.Field.FOOD);
          html += "food: "+food;
          html += "<br>";

   
		  var turnOns = vf.getField(opensocial.Person.TURN_ONS);
		  html += "turnOns: "+turnOns;
		  html += "<br>";



		  var turnOffs = vf.getField(opensocial.Person.Field.TURN_OFFS);
		  html += "turnOffs: "+turnOffs;
		  html += "<br>";
		  //
		  var tags = vf.getField(opensocial.Person.Field.TAGS);
		  html += "tags: "+tags;
		  html += "<br>";



		  var romance = vf.getField(opensocial.Person.Field.ROMANCE);
		  html += "romance: "+romance;
		  html += "<br>";

		  	      var scaredOf = vf.getField(opensocial.Person.Field.SCARED_OF);
          html += "scaredOf: "+scaredOf;
          html += "<br>";
          
		  
		  var happiestWhen = vf.getField(opensocial.Person.Field.HAPPIEST_WHEN);
          html += "happiestWhen: "+happiestWhen;
          html += "<br>";

   
		  var fashion = vf.getField(opensocial.Person.FASHION);
		  html += "fashion: "+fashion;
		  html += "<br>";



		  var humor = vf.getField(opensocial.Person.Field.HUMOR);
		  html += "humor: "+humor;
		  html += "<br>";
		  //
		  	  	  	  var lookingFor = vf.getField(opensocial.Person.Field.LOOKING_FOR);
		  html += "lookingFor: "+lookingFor;
		  html += "<br>";



		  var religion = vf.getField(opensocial.Person.Field.RELIGION);
		  html += "religion: "+religion;
		  html += "<br>";

		  var politicalViews = vf.getField(opensocial.Person.Field.POLITICAL_VIEWS);
          html += "politicalViews: "+politicalViews;
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
        