        function loadViewer() {
			    //var oid = id;
				var req = opensocial.newDataRequest();
				var opt_params = {};
				opt_params[opensocial.DataRequest.PeopleRequestFields.PROFILE_DETAILS] =
										[
											//姓名
											opensocial.Person.Field.NAME,
											
											//姓名
											opensocial.Person.Field.NICKNAME,
											
											//性别
											opensocial.Person.Field.GENDER,
											
											//居住地-省、市
											opensocial.Person.Field.CURRENT_LOCATION,
											
											//email
											opensocial.Person.Field.EMAILS,
											
											//DATE_OF_BIRTH
											opensocial.Person.Field.DATE_OF_BIRTH,
											
											//IMS
											opensocial.Person.Field.IMS,
											
											//状态
											opensocial.Person.Field.STATUS,
											
											//婚恋状态
											opensocial.Person.Field.RELATIONSHIP_STATUS,
											
											//HAS_APP
											opensocial.Person.Field.HAS_APP,
											
											//ABOUT_ME
											opensocial.Person.Field.ABOUT_ME,

										];
                req.add(req.newFetchPersonRequest('VIEWER',opt_params), 'viewer');	
				//req.add(req.newFetchPersonRequest('VIEWER',opt_params), 'viewer');	
				req.send(onLoadViewer);
			}

			function onLoadViewer(data) {
				var viewer = data.get('viewer').getData();
				var html = "";

				var isOwner = viewer.isOwner();
				html += "isOwner: "+isOwner;
				html += "<br>";
				
				var isViewer = viewer.isViewer();
				html += "isViewer: "+isViewer;
				html += "<br>";
				
				var displayName = viewer.getDisplayName();
				html += "Username: "+displayName;
				html += "<br>";
				
				var nickName = viewer.getDisplayName();
				html += "Nickname: "+nickName;
				html += "<br>";
				
				var id = viewer.getField(opensocial.Person.Field.ID);
				html += "Opensocial-ID: "+id;
				html += "<br>";
				
				var name = viewer.getField(opensocial.Person.Field.NAME);
				html += "Name: "+name.getField(opensocial.Name.Field.UNSTRUCTURED);
				html += "<br>";
				
				var gender = viewer.getField(opensocial.Person.Field.GENDER);
				if(gender){
					html += "Gender: "+gender.getDisplayValue();
					html += "<br>";
				}
				
				var location =viewer.getField(opensocial.Person.Field.CURRENT_LOCATION);
				if(location!=undefined){
					html += "Provice: "+location.getField(opensocial.Address.Field.REGION);
					html += "<br>";
					html += "City: "+location.getField(opensocial.Address.Field.LOCALITY);
					html += "<br>";
				}				
				
				var thumbnail_url = viewer.getField(opensocial.Person.Field.THUMBNAIL_URL);
				html += "Thumbnail-URL: "+thumbnail_url;
				html += "<br>"
				
				var profile_url = viewer.getField(opensocial.Person.Field.PROFILE_URL);
				html += "Profile-URL: "+profile_url;
				html += "<br>"
				
				
				var emails = viewer.getField(opensocial.Person.Field.EMAILS);
				if(emails!=undefined){
					html += "Email: "+emails[0].getField(opensocial.Email.Field.ADDRESS);
					html += "<br>"
				}
				
			
				var birth = viewer.getField(opensocial.Person.Field.DATE_OF_BIRTH);
				if(birth){
					html += "Birthday: "+birth.toDateString();
					html += "<br>"
				}
				
				
				var ims = viewer.getField(opensocial.Person.Field.IMS);
				if(ims){
					html += "IMS: "+ims;
					html += "<br>"
				}
				
				var status = viewer.getField(opensocial.Person.Field.STATUS);
				if(status){
					html += "Status: "+status;
					html += "<br>"
				}
				
				var relationship_status = viewer.getField(opensocial.Person.Field.RELATIONSHIP_STATUS);
				if(status){
					html += "Relationship: "+relationship_status;
					html += "<br>"
				}
				
				var HAS_APP = viewer.getField(opensocial.Person.Field.HAS_APP);
				if(status){
					html += "HAS_APP: "+HAS_APP;
					html += "<br>"
				}
				var ABOUT_ME = viewer.getField(opensocial.Person.Field.ABOUT_ME);
				if(status){
					html += "ABOUT_ME: "+ABOUT_ME;
					html += "<br>"
				}
			
				document.write(html);
                document.close();                           
			};
        