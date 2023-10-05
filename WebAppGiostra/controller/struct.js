          
class Struct{


  itemOption(list){

    if(getCookie("userInfo") != ""){
        var item = null;
        if(getCookie("userInfo") != ""){
          var info = JSON.parse(getCookie("userInfo"));
          for (var i =0; i < list.length; i++) {
            if(list[i]['id'] == info['borgo']){
              item += "<option class=\"borgo"+list[i]['id']+"\" value=\""+list[i]['id']+"\"><b>"+list[i]['nome']+"</b></option>";
            }
          }
        }

        item += "<option></option>";

        for (var i =0; i < list.length; i++) {
            item += "<option class=\"borgo"+list[i]['id']+"\" value=\""+list[i]['id']+"\">"+list[i]['nome']+"</option>";
        }


          $("#linkBorghiList").html(item);
        

    }else{

    }

  }


  itemEvent(list,reservation){

    var item = "";
    for (var i =0; i < list.length; i++) {

        item += '<div class="col-md-12 eventitemlist">';
          item += '<div class="col-md-2">';
            item += '<img src="'+list[i]['stemma']+'">'; 
          item += '</div>';
          item += '<div class="col-md-10 h-entry">';
            item += '<h2 class="font-size-regular"><a class="linkViewEvent" href="#" class="text-black">'+list[i]['name']+'</a></h2>';
            item += '<div class="meta mb-3">'+list[i]['nome']+'<br>'+list[i]['date']+'</div>';
            item += '<p>'+list[i]['descrizione']+'</p>';
            item += '<div class="meta mb-3">Remaining Seats: '+list[i]['remainingplaces']+'</div>';

            var control = false;
            if (reservation != null) {
              for (var k =0; k < reservation.length; k++) {
                if (reservation[k]['id_event'] == list[i]['id']) {
                  if (reservation[k]['state'] == 1) {
                    item += '<div class="meta mb-3">Booking ate: '+list[i]['date']+'</div>';
                    item += '<div class="meta mb-3 text-success"><b>SEATS RESERVED FOR YOU: '+reservation[k]['places']+'</b></div>';
                  }else{
                    item += '<div class="meta mb-3">Booking date: '+list[i]['date']+'</div>';
                    item += '<div class="meta mb-3 text-warning"><b>PENDING FOR CONFIRMATION OF '+reservation[k]['places']+' POSTS</b></div>';
                  }
                  control = true
                }
              }
            }

            if(list[i]['places'] > 0 && control != true && getCookie("userInfo") != ""){
            item += '<select class="selectpicker" onchange="inscription('+list[i]['borgo']+','+list[i]['id']+',this.value)">';
              item += '<option value="0">Not Booked</option>';
              item += '<option value="1">1 - PEOPLE</option>';
              item += '<option value="2">2 - PEOPLE</option>';
              item += '<option value="3">3 - PEOPLE</option>';
              item += '<option value="4">4 - PEOPLE</option>';
              item += '<option value="5">5 - PEOPLE</option>';
              item += '<option value="6">6 - PEOPLE</option>';
              item += '<option value="7">7 - PEOPLE</option>';
              item += '<option value="8">8 - PEOPLE</option>';
            item += '</select>';
            }
          item += '</div>'; 
        item += '</div>';

    }

    $("#eventsList").html(item);
    

  }

  itemBorghi(list){

    var item = "";
    for (var i =0; i < list.length; i++) {
        item += '<li><button class="customButton" onclick="about('+list[i]['id']+')">'+list[i]['nome']+'</button></li>';
    }

    $("#listOfBorghi").html(item);
    

  }


  listPalii(list){

    var item = "";
    for (var i =0; i < list.length; i++) {
        item += '<tr><td>'+list[i]['anno']+'</td><td>'+list[i]['autore']+'</td><td>'+list[i]['cavaliere']+'</td></tr>';
    }


    $("#itemListPalii").html(item);
    

  }

  itemListEvent(list){


    if(list){
      var item = '<div id="accordion">';

      for (var i =0; i < list.length; i++) {

          item += '<div class="card">';
            item += '<div class="card-header" id="headingOne">';
              item += '<h4 class="mb-0">';
                item += '<button class="btn btn-danger py-1 px-4" onclick="removeEvent('+list[i]['id']+')">Remove</button>';
                item += '<button style="margin-left:3px;" class="btn btn-light py-1 px-4" onclick="createEventEditView('+list[i]['id']+')">Edit</button>';
                item += '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne'+i+'" aria-expanded="true" aria-controls="collapseOne" onclick="getListUsers('+list[i]['id']+','+i+')">'+list[i]['date']+' - <b>'+list[i]['name']+'</b></button>';
              item += '</h4>';
            item += '</div>';

            item += '<div id="collapseOne'+i+'" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">';
              item += '<div class="card-body">';
                item += '<span>Seats Available <b>'+list[i]['places']+'</b></span><br>';
                item += '<span>Bookings Total <b>'+list[i]['remainingplaces']+'</b></span><br>';
                if(list[i]['state'] == 0){
                  item += '<span>Publication status <b class="text-danger">INACTIVE</b></span>';
                }else{
                  item += '<span>Publication status <b class="text-success">ACTIVE</b></span>';
                }

                  item += '<table style="margin-top:15px;" class="table user-list">';
                      item += '<thead>';
                        item += '<th><span>Name</span></th>';
                        item += '<th><span>Surname</span></th>';
                        item += '<th><span>Email</span></th>';
                        item += '<th class="text-center"><span>Reserved Seats</span></th>';
                        item += '<th class="text-center"><span>Action</span></th>';
                                                              
                      item += '</thead>';
                      item += '<tbody id="itemListUsersEvent'+i+'"></tbody>';
                      item += '</div>';
                  item += '</table>';

              item += '</div>';
            item += '</div>';
          item += '</div>';

      }

      item += '</div>';

      $("#itemListEvent").html(item);

    }else{
      $("#itemListEvent").html("");
    }

  }



  infoItemEvent(event,list,element){

    var item = "";

    if (list == null) {
      item += '<tr><td><b>There are no subscribers to the event yet</b></td></tr>';
    }else{
      for (var i =0; i < list.length; i++) {

          item += '<tr>';
            item += '<td>'+list[i]['name']+'</td>';
            item += '<td>'+list[i]['surname']+'</td>';
            item += '<td>'+list[i]['email']+'</td>';
            item += '<td class="text-center">'+list[i]['places']+'</td>';
            item += '<td class="text-center">';
            if(list[i]['state'] == 1){
              item += '<span class="text-success"><b>Confirmed</b></span>';
            }else{
              item += '<button style="margin-left:3px;" class="btn btn-warning py-1 px-4" onclick="confirmationOfReservation('+event+','+list[i]['id']+','+element+')">Confirmation</button>';
            }
            item += '</td>';
          item += '</tr>';

      }
    }


    $("#itemListUsersEvent"+element).html(item);
    

  }



  itemListUsers(list,roles){

    var item = "";
    for (var i =0; i < list.length; i++) {

        item += '<tr>';
          item += '<td><span class="user-subhead">'+list[i]['name']+'</span></td>';
          item += '<td><span class="user-subhead">'+list[i]['surname']+'</span></td>';
          item += '<td><span class="user-subhead">'+list[i]['email']+'</span></td>';
          item += '<td><span class="user-subhead">'+list[i]['year']+'</span></td>';

          if(list[i]['state'] == 1)
            item += '<td class="text-center"><button style="border-radius:50%;" class="btn btn-success" onclick="deactivatesAccount('+list[i]['id']+')">&CirclePlus;</button></td>';
          else item += '<td class="text-center"><button style="border-radius:50%;" class="btn btn-danger" onclick="activateAccount('+list[i]['id']+')">&CircleTimes;</button></td>';

          item += '<td class="text-center">';
            item += '<span class="label label-default">';
            item += '<select class="selectpicker" onchange="roleChar('+list[i]['id']+',this.value);">';
              for (var j = 0; j < roles.length ; j++) {
                  if(list[i]['id_role'] == roles[j]['id'])
                    item += '<option value="'+roles[j]['id']+'">'+roles[j]['name']+'</option>';
              }
              for (var j = 0; j < roles.length ; j++) {
                  if(list[i]['id_role'] != roles[j]['id'])
                    item += '<option value="'+roles[j]['id']+'">'+roles[j]['name']+'</option>';
              }              
            item += '</select>';
            item += '</span>';
          item += '</td>';
        item += '</tr>';

    }


    $("#itemListUsers").html(item);

  }



}
