jQuery(function($) {

  // multi select

  $('.select2').select2({
    closeOnSelect: false
    });

  // freelancer form submit ajax
  $("html").on("click", "#freelancer", function() {
  $("#freelancer").validate(
    {
        rules: 
        {
            email:          {   required:true, email: true },
            password:       {   required:true },   
            first_name:       {   required:true },     
            last_name:       {   required:true }, 
            username:       {   required:true }, 
            skills:       {   required:true }, 
            re_password:       {   required:true }, 
        },
        submitHandler: function(form)
        {
    
    // $('#freelancer').submit(function(e){
    //     e.preventDefault();

        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var email = $("#email").val();
        var username = $("#username").val();
        var skills = $("#skills").val();
        var password = $("#password").val();
        var re_password = $("#re_password").val();

       alert(skills);
        
        $.ajax({ 
            data: {
                action: 'get_freelancer_details',
                first_name:first_name,
                last_name:last_name,
                email:email,
                username:username,
                skills:skills,
                password:password,
                re_password:re_password,
               },
            type: 'post',
            dataType: 'html',
            url:ajax_params.ajax_url,
            success: function(data) {  
                console.log(data);
                jQuery(".message-show").html(data);

           }
       });
       return false;
      }
    });

  });


    // check user name 

   // $("#username").keyup(function(){
      $('#username, #email').on('input', function(e) {
 
        var username = $('#username').val();
        var email = $('#email').val();
     
  
        if(username != '' || email != ''){
          // alert(username);
          // alert(email);
  
           $.ajax({
            data: {
                action: 'check_user_name',
                username:username,
                email:email,
               },
            type: 'post',
            dataType: 'html',
            url:ajax_params.ajax_url,
              success: function(response){
                 console.warn(response);
  
                  if(response=="user_email"){
                    $('.check-error').html('Email already exist');
                    $('#submit-btn').prop('disabled', true);
                  } else if(response=="user_name"){
                    $('.check-error').html('User already exist');
                    $('#submit-btn').prop('disabled', true);
                  } else {
                    $('.check-error').html('');
                    $('#submit-btn').prop('disabled', false);
                  }
               }
           });
        }else{
         //  $("#uname_response").html("");
        }
  
      });


       // Company form submit ajax

       $("#company").validate(
        {
            rules: 
            {
                email:          {   required:true, email: true },
                password:       {   required:true },   
                company_name:       {   required:true },     
                company_url:       {   required:true }, 
                re_password:       {   required:true }, 
            },
            submitHandler: function(form)
            {
        
    
    // $('#company').submit(function(e){
    //   e.preventDefault();

      var company_name = $("#company_name").val();
      var company_url = $("#company_url").val();
      var email = $("#email").val();
      var password = $("#password").val();
      var re_password = $("#re_password").val();

    //  alert(first_name);
      
      $.ajax({ 
          data: {
              action: 'get_company_details',
              company_name:company_name,
              company_url:company_url,
              email:email,
              password:password,
              re_password:re_password,
             },
          type: 'post',
          dataType: 'html',
          url:ajax_params.ajax_url,
          success: function(data) {  
              console.log(data);
            jQuery(".message-show").html(data)

         }
     });
     return false;
    }
  });


  // Login form
  $("#signinForm").validate(
    {
        rules: 
        {
            email:          {   required:true, email: true },
            password:       {   required:true }     
        },
        submitHandler: function(form)
        {
            var user_email = $( "#user_email" ).val();
            var user_password = $( "#user_password" ).val();
     
            $.ajax({ 
              data: {
                  action: 'get_login_details',
                  user_email:user_email,
                  user_password:user_password,
                 },
              type: 'post',
              url:ajax_params.ajax_url,
              success: function(responseData) {  
                 console.log(responseData);
                  if( responseData) {
                     window.location = responseData;
            }
            else {
                    jQuery(".error-msg").html('User Not Exist');
            }
    
             }
         });
            return false;
        }
    });


    // Logout user

    $("#logout").click(function(){
     // alert("hhahha");
      var data_id = $(this).val();
      $.ajax({ 
        data: {
            action: 'get_logout',
            data_id:data_id,
           },
        type: 'post',
        url:ajax_params.ajax_url,
        success: function(responseData) {  
           console.log(responseData);
            if( responseData) {
               window.location = responseData;
                }
       }
    });


    });


      // Proposal form 
$("html").on("click", "#proposalForm", function() {
  $("#proposalForm").validate(
    {
        rules: 
        {
            Proposal_text:       {   required:true },    
            bidding_price:       {   required:true,digits:true }     

        },
        submitHandler: function(form)
        {
            var Proposal_text = $( "#Proposal_text" ).val();
            var post_id = $( "#post_id" ).val();
            var user_id = $( "#user_id" ).val();
            var bidding_price = $( "#bidding_price" ).val();


  
            $.ajax({ 
              data: {
                  action: 'get_proposal_details',
                  Proposal_text:Proposal_text,
                  post_id:post_id,
                  user_id:user_id,
                  bidding_price:bidding_price,

                 },
              type: 'post',
              url:ajax_params.ajax_url,
              success: function(responseData) {  
                 console.log(responseData);
                 jQuery("#proposal-success").html("Proposal submit successfully");
                 setTimeout(function(){
                  location.reload();
             }, 2000); 
             }
         });
            return false;
        }
    });
  });


        // get my feed jobs

        $("#my_feed").click(function(){
         //  alert("hhahha");
         var user_id = $( "#user_id" ).val();
           $.ajax({ 
             data: {
                 action: 'get_feed_jobs',
                 user_id:user_id,
                },
             type: 'post',
             url:ajax_params.ajax_url,
             success: function(responseData) {  
              //  console.log(responseData);
                jQuery("#jobs-list").html(responseData);
            }
         });
     
     
         });
     
         
        // get all jobs

        $("#all_jobs").click(function(){
          //  alert("hhahha");
          var user_id = $( "#user_id" ).val();
            $.ajax({ 
              data: {
                  action: 'get_all_jobs',
                  user_id:user_id,
                 },
              type: 'post',
              url:ajax_params.ajax_url,
              success: function(responseData) {  
               //  console.log(responseData);
                 jQuery("#jobs-list").html(responseData);
             }
          });
      
      
          });

        // get single jobs
          $("html").on("click", ".apply_jobs", function() {
            var job_id = $(this).data('job_id');
           //  var job_id = $( "#job_id" ).val();
            $.ajax({ 
              data: {
                  action: 'get_single_jobs',
                  job_id:job_id,
                 },
              type: 'post',
              url:ajax_params.ajax_url,
              success: function(responseData) {  
                 jQuery("#jobs-list").html(responseData);
             }
            });
        });

        // freelancer profile update
        $("html").on("click", "#freelancer_profile", function() {
       //  alert("ajaja");
         var user_id = $( "#user_id" ).val();
          $.ajax({ 
            data: {
                action: 'update_freelancer_profile',
                user_id:user_id,
                },
            type: 'post',
            url:ajax_params.ajax_url,
            success: function(responseData) {  
                jQuery("#jobs-list").html(responseData);
                $('.select2').select2({
                  closeOnSelect: false
                  });
            }
          });
      });

      // update profile skill 

      $("html").on("change", ".select2", function() {

      $('.select2').select2({
        multiple: "multiple",
      });
    });

    // freelancer profile update submit ajax
  $("html").on("click", "#freelancer_profile_update", function() {
    $("#freelancer_profile_update").validate(
      {
          rules: 
          {
              email:          {   required:true, email: true },
              password:       {   required:true },   
              first_name:       {   required:true },     
              last_name:       {   required:true }, 
              username:       {   required:true }, 
              skills:       {   required:true }, 
              re_password:       {   required:true }, 
          },
          submitHandler: function(form)
          {
      
      // $('#freelancer').submit(function(e){
      //     e.preventDefault();
  
          var first_name = $("#first_name").val();
          var last_name = $("#last_name").val();
          var email = $("#email").val();
          var username = $("#username").val();
          var skills = $("#skills").val();
          var password = $("#password").val();
          var re_password = $("#re_password").val();
          var user_id = $("#user_id").val();
  
         alert(skills);
          
          $.ajax({ 
              data: {
                  action: 'get_freelancer_profile_update',
                  first_name:first_name,
                  last_name:last_name,
                  email:email,
                  username:username,
                  skills:skills,
                  password:password,
                  re_password:re_password,
                  user_id:user_id,
                 },
              type: 'post',
              dataType: 'html',
              url:ajax_params.ajax_url,
              success: function(data) {  
                  console.log(data);
                  jQuery(".message-show").html(data)
  
             }
         });
         return false;
        }
      });
  
    });

  // Get All proposal
  $("html").on("click", "#all_proposal", function() {
    //  alert("ajaja");
      var user_id = $( "#user_id" ).val();
        $.ajax({ 
          data: {
              action: 'get_all_proposal',
              user_id:user_id,
              },
          type: 'post',
          url:ajax_params.ajax_url,
          success: function(responseData) {  
              jQuery("#jobs-list").html(responseData);
          }
        });
    });

    // get all jobs for company 

    $("#company_all_jobs").click(function(){
      //  alert("hhahha");
      var user_id = $( "#user_id" ).val();
        $.ajax({ 
          data: {
              action: 'get_all_jobs_in_company',
              user_id:user_id,
              },
          type: 'post',
          url:ajax_params.ajax_url,
          success: function(responseData) {  
            //  console.log(responseData);
              jQuery("#company-content").html(responseData);
          }
      });


      });

    // company profile update
    $("html").on("click", "#company_profile", function() {
    //  alert("ajaja");
      var user_id = $( "#user_id" ).val();
        $.ajax({ 
          data: {
              action: 'update_company_profile',
              user_id:user_id,
              },
          type: 'post',
          url:ajax_params.ajax_url,
          success: function(responseData) {  
              jQuery("#company-content").html(responseData);
          }
        });
    });

              

    

     // Company profile update submit ajax
  $("html").on("click", "#company_profile_update", function() {
          $("#company_profile_update").validate(
            {
                rules: 
                {
                    email:          {   required:true, email: true },
                    password:       {   required:true },   
                    company_name:       {   required:true },     
                    company_url:       {   required:true }, 
                    re_password:       {   required:true }, 
                },
                submitHandler: function(form)
                {
            

          // $('#company').submit(function(e){
          //   e.preventDefault();

          var company_name = $("#company_name").val();
          var company_url = $("#company_url").val();
          var email = $("#email").val();
          var password = $("#password").val();
          var re_password = $("#re_password").val();
          var user_id = $("#user_id").val();

         //alert(user_id);

          $.ajax({ 
              data: {
                  action: 'get_company_profile_update',
                  company_name:company_name,
                  company_url:company_url,
                  email:email,
                  password:password,
                  re_password:re_password,
                  user_id:user_id,
                  },
              type: 'post',
              dataType: 'html',
              url:ajax_params.ajax_url,
              success: function(data) {  
                  console.log(data);
                jQuery(".message-show").html(data)

              }
          });
          return false;
          }
          });
  
    });


   // Create a job

   $("#create_jobs").click(function(){
    //  alert("hhahha");
    var user_id = $( "#user_id" ).val();
      $.ajax({ 
        data: {
            action: 'create_a_job',
            user_id:user_id,
            },
        type: 'post',
        url:ajax_params.ajax_url,
        success: function(responseData) {  
          //  console.log(responseData);
            jQuery("#company-content").html(responseData);
            $('.select2').select2({
              closeOnSelect: false
              });
        }
    });

    });


    // submit new job

    $("html").on("click", "#submit_job", function() {
      $("#submit_job").validate(
        {
            rules: 
            {
                job_title:          {   required:true },
                job_description:       {   required:true },   
                skills:       {   required:true },  
                job_type_dropdown:       {   required:true }, 
                fixed_price:{digits: true},  
                min_price:{digits: true},  
                max_price:{digits: true},  

                
                
            },
            submitHandler: function(form)
            {
            var job_title = $("#job_title").val();
            var job_description = $("#job_description").val();
            var skills = $("#skills").val();
            var user_id = $( "#user_id" ).val();
            var fixed_price = $( "#fixed_price" ).val();
            var min_price = $( "#min_price" ).val();
            var max_price = $( "#max_price" ).val();
          //  alert(skills);
            $.ajax({ 
                data: {
                    action: 'get_job_post_details',
                    job_title:job_title,
                    job_description:job_description,
                    skills:skills,
                    user_id:user_id,
                    fixed_price:fixed_price,
                    min_price:min_price,
                    max_price:max_price,
                   },
                type: 'post',
                dataType: 'html',
                url:ajax_params.ajax_url,
                success: function(data) {  
                    console.log(data);
                    jQuery(".message-show").html(data);
    
               }
           });
           return false;
          }
        });
    
      });

        // get proposal
        $("html").on("click", ".view_proposal", function() {
          var job_id = $(this).data('job_id');
          var user_id = $( "#user_id" ).val();
         //  var job_id = $( "#job_id" ).val();
          $.ajax({ 
            data: {
                action: 'get_proposals_details',
                job_id:job_id,
                user_id:user_id,
               },
            type: 'post',
            url:ajax_params.ajax_url,
            success: function(responseData) {  
               jQuery("#company-content").html(responseData);
           }
          });
      });

      // accept proposal
      $("html").on("click", "#accept_proposal", function() {
      var job_id = $(this).data('job_id');
      var user_id = $(this).data('user_id');
      //  var job_id = $( "#job_id" ).val();
      $.ajax({ 
        data: {
            action: 'accept_proposal_function',
            job_id:job_id,
            user_id:user_id,
            },
        type: 'post',
        url:ajax_params.ajax_url,
        success: function(responseData) {  
          $.ajax({ 
            data: {
                action: 'get_proposals_details',
                job_id:job_id,
                user_id:user_id,
               },
            type: 'post',
            url:ajax_params.ajax_url,
            success: function(responseData) {  
               jQuery("#company-content").html(responseData);
           }
          });

        }
      });
  });

      // Reject proposal
      $("html").on("click", "#reject_proposal", function() {
        var job_id = $(this).data('job_id');
        var user_id = $(this).data('user_id');
        $.ajax({ 
          data: {
              action: 'reject_proposal_function',
              job_id:job_id,
              user_id:user_id,
              },
          type: 'post',
          url:ajax_params.ajax_url,
          success: function(responseData) {  
            $.ajax({ 
              data: {
                  action: 'get_proposals_details',
                  job_id:job_id,
                  user_id:user_id,
                 },
              type: 'post',
              url:ajax_params.ajax_url,
              success: function(responseData) {  
                 jQuery("#company-content").html(responseData);
             }
            });
          }
        });
    });

    //  price base on select
    $("html").on("change", "#job_type_dropdown", function() {
      var inputVal = $(this).val();
      var eleBox = $("." + inputVal);
      $(".job-options").hide();
      $(eleBox).show();
  });
    // //////// 
      
});

