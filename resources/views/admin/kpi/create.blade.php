@extends('admin.layouts.app')

@section('title', 'Create Kpi')
@section('stylesheets')
<link rel="stylesheet" href="{{asset('adminlte/component/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
<style>
  .direct-chat-img {
      object-fit: contain;
      border:1px #d2d6de solid;
  }
  .direct-chat-messages {
    height: 430px !important;
  }
  .direct-chat-text{
    margin-right: 20% !important;
  }
  .right .direct-chat-text {
    margin-right: 10px !important;
    float: right;
  }
  .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"], .radio input[type="radio"], .radio-inline input[type="radio"] {
      position: unset;
      margin-left: 0;
  }
  .dot-typing {
    position: relative;
    left: -9999px;
    width: 8px;
    height: 8px;
    border-radius: 5px;
    background-color: #444;
    color: #444;
    box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    animation: dotTyping 1.5s infinite linear;
  }

  @keyframes dotTyping {
    0% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
    16.667% {
      box-shadow: 9984px -10px 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
    33.333% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
    50% {
      box-shadow: 9984px 0 0 0 #444, 9999px -10px 0 0 #444, 10014px 0 0 0 #444;
    }
    66.667% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
    83.333% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px -10px 0 0 #444;
    }
    100% {
      box-shadow: 9984px 0 0 0 #444, 9999px 0 0 0 #444, 10014px 0 0 0 #444;
    }
  }
</style>
@endsection

@push('breadcrump')
<li class="breadcrumb-item active"><a href="{{route('account.index')}}">Kpi</a></li>
<li class="breadcrumb-item active">Create</li>
@endpush

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-{{ config('configs.app_theme') }} card-outline direct-chat direct-chat-primary">
      <div class="card-header">
        <h3 class="card-title">Create KPI</h3>
        {{-- <div class="float-right card-tools">
          <button form="form" type="submit" class="btn btn-sm btn-{{ config('configs.app_theme') }}" title="Simpan"><i
              class="fa fa-save"></i></button>
          <a href="{{ url()->previous() }}" class="btn btn-sm btn-default" title="Kembali"><i
              class="fa fa-reply"></i></a>
        </div> --}}
      </div>
      <div class="card-body">
        <form id="form" action="{{ route('kpi.store') }}" class="form-horizontal" method="post" autocomplete="off">
          {{ csrf_field() }}
          <div class="direct-chat-messages">
          </div>
          <input type="hidden" name="employee_id" value="{{$employee_id}}">
          <input type="hidden" name="result_date" value="{{$result_date}}">
        </form>
      </div>
      <div class="overlay d-none">
        <i class="fa fa-2x fa-sync-alt fa-spin"></i>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('adminlte/component/validate/jquery.validate.min.js')}}"></script>
<script>
  var kpis = [];
  var questions = [];
  var question_childs = [];
  var answers = [];
  var answer_questions = [];
  var actions = [];
  var start = 0;

  $.each(@json($actions, JSON_PRETTY_PRINT), function(i, message) {  
    if(!actions[message.question_id]){
      actions[message.question_id] = [];
      actions[message.question_id].push(message);
    }
    else{
      actions[message.question_id].push(message);
    }
  });
  $.each(@json($questions, JSON_PRETTY_PRINT), function(i, message) {
    if (message.is_parent == 0) {  
        questions.push(message);
    }
  });
  $.each(@json($questions, JSON_PRETTY_PRINT), function(i, message) {
    if (message.is_parent == 1) {  
        question_childs[message.answer_parent_code] = message;
    }
  });
  $.each(@json($questions, JSON_PRETTY_PRINT), function(i, message) { 
      kpis[message.id] = message;
  });
  $.each(@json($answers, JSON_PRETTY_PRINT), function(i, message) { 
    if(!answers[message.question_id]){
      answers[message.question_id] = [];
      answers[message.question_id].push(message);
    }
    else{
      answers[message.question_id].push(message);
    }
    
  });
  $.each(@json($answers, JSON_PRETTY_PRINT), function(i, message) { 
    answer_questions[message.id] = message;
  });
  function loader(){
    if(start == questions.length){
      var complete = true;
      $(".direct-chat-msg").each(function() {
          if($(this).hasClass('answer')){
            if($(this).is(':visible')){
              $('.direct-chat-messages').append(`
              <div class="direct-chat-msg error" >
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
                </div>
                <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
                <div class="direct-chat-text">
                  Anda belum menutup semua isian , check kembali isian anda. <br/>
                  Pilih ya jika sudah melengkapi isian.
                </div>
              </div>
            `);
            $('.direct-chat-messages').append(`
              <div class="direct-chat-msg right" id="error_answer">
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name float-right">{{$employee->name}}</span>
                </div>
                <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
                <div class="direct-chat-text float-right">
                  <input type="radio" value="1" onclick="reload(this)">
                  Ya <br/><input type="radio" value="0" onclick="reload(this)"> Muat Ulang
                </div>
              </div>`);

              $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
              complete = false;
            }
          }
      });
      if(!complete){
        return;
      }
      $.ajax({
          url:'{{route('kpi.check')}}',
          method:'post',
          data: new FormData($('#form')[0]),
          processData: false,
          contentType: false,
          dataType: 'json', 
          beforeSend:function(){
            $('.direct-chat-messages').append(`
                <div class="direct-chat-msg loader">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
                  </div>
                  <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
                  <div class="direct-chat-text">
                    <div style="padding:5px 0 5px 15px">
                      <div class="dot-typing"></div>
                    </div>
                  </div>
                </div>
              `);
          }
        }).done(function(response){
              $('.loader').remove();
              if(response.status){
                 if(response.answer > 0){
                    $('.direct-chat-messages').append(`
                      <div class="direct-chat-msg loader">
                        <div class="direct-chat-info clearfix">
                          <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
                        </div>
                        <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
                        <div class="direct-chat-text">
                          <div style="padding:5px 0 5px 15px">
                            <div class="dot-typing"></div>
                          </div>
                        </div>
                      </div>
                    `);
                    setTimeout(function() {
                      $('.loader').remove();
                      $('.direct-chat-messages').append(`
                        <div class="direct-chat-msg">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
                          </div>
                          <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
                          <div class="direct-chat-text">
                            Simpan data KPI?
                            </div>
                        </div>
                      `);
                      $('.direct-chat-messages').append(`
                        <div class="direct-chat-msg right" id="finish_answer">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name float-right">{{$employee->name}}</span>
                          </div>
                          <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
                          <div class="direct-chat-text float-right">
                            <input type="radio" value="1" onclick="finish(this)">
                            Ya <br/><input type="radio" value="0" onclick="finish(this)"> Muat Ulang
                          </div>
                        </div>
                    `);
                    }, 1000);
                 }
              }else{
                $('.direct-chat-messages').append(`
                  <div class="direct-chat-msg error">
                    <div class="direct-chat-info clearfix">
                      <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
                    </div>
                    <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
                    <div class="direct-chat-text">
                      Maaf {{config('configs.bot_username')}} sedang mengalami gangguan :( <br/> Apakah anda mau melanjutkan pengisian ?
                      </div>
                  </div>
                `);
              $('.direct-chat-messages').append(`
                <div class="direct-chat-msg right" id="error_answer">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name float-right">{{$employee->name}}</span>
                  </div>
                  <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
                  <div class="direct-chat-text float-right">
                    <input type="radio" value="1" onclick="reload(this)">
                    Ya <br/><input type="radio" value="0" onclick="reload(this)"> Muat Ulang
                  </div>
                </div>`);
              }
              $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
              return;
        }).fail(function(response){
            $('.loader').remove();
            $('.direct-chat-messages').append(`
                <div class="direct-chat-msg error" >
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
                  </div>
                  <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
                  <div class="direct-chat-text">
                    Maaf {{config('configs.bot_username')}} sedang mengalami gangguan :( <br/> Apakah anda mau melanjutkan pengisian ?
                    </div>
                </div>
              `);
            $('.direct-chat-messages').append(`
              <div class="direct-chat-msg right" id="error_answer">
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name float-right">{{$employee->name}}</span>
                </div>
                <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
                <div class="direct-chat-text float-right">
                  <input type="radio" value="1" onclick="reload(this)">
                  Ya <br/><input type="radio" value="0" onclick="reload(this)"> Muat Ulang
                </div>
              </div>`);

            $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
        })	
      return;
    }
    $('.direct-chat-messages').append(`
      <div class="direct-chat-msg loader">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
        </div>
        <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
        <div class="direct-chat-text">
          <div style="padding:5px 0 5px 15px">
            <div class="dot-typing"></div>
          </div>
        </div>
      </div>
    `);
    $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000);
    setTimeout(function() {
      bot();
    }, 1000);
  }
  function bot(){
    $('.loader').remove();
    var message = '';
    switch(questions[start].type){
      case 'Pertanyaan':
            message = questions[start].description;
            write(message,questions[start].id);
            user();
            break;
      case 'Informasi' :
            message = questions[start].description_information;
            write(message,questions[start].id);
            setTimeout(function() {
              start++;
              loader();
            }, 1000);
            break;
        case 'Informasi Dan Pertanyaan' :
            if(actions[questions[start].id]){
              message = questions[start].description_information;
              switch(questions[start].answer_type){
                case 'checkbox':
                        var answerbefore = [];
                        $.each(actions[questions[start].id],function(){
                          answerbefore.push(this.answer.description);
                        })
                        message = message.replace('[answer]',`<b>${answerbefore.join(',')}</b>`);
                        message = message.replace('[question]',`<b>${questions[start].description}</b>`);
                        message = message.replace('[update]',`<a onclick="reset(${questions[start].id})" style="cursor:pointer">[Disini]</a>`);
                      break;
                case 'radio':
                      message = message.replace('[answer]',`<b>${actions[questions[start].id][0].answer.description}</b>`);
                      message = message.replace('[question]',`<b>${questions[start].description}</b>`);
                      message = message.replace('[update]',`<a onclick="reset(${questions[start].id})" style="cursor:pointer">[Disini]</a>`);
                      break;
                case  'text':
                      message = message.replace('[answer]',`<b>${actions[questions[start].id][0].description}</b>`);
                      message = message.replace('[question]',`<b>${questions[start].description}</b>`);
                      message = message.replace('[update]',`<a onclick="reset(${questions[start].id})" style="cursor:pointer">[Disini]</a>`);
                      break;
                case  'number':
                      message = message.replace('[answer]',`<b>${actions[questions[start].id][0].description}</b>`);
                      message = message.replace('[question]',`<b>${questions[start].description}</b>`);
                      message = message.replace('[update]',`<a onclick="reset(${questions[start].id})" style="cursor:pointer">[Disini]</a>`);
                      break;
              }
              write(message,questions[start].id);
              user(1);
            }
            else{
              message = questions[start].description;
              write(message,questions[start].id);
              user();
            }
            break;
      default:
            message = questions[start].description;
            write(message,questions[start].id);
            
    }
  }
  function botchild(id){
    $('.loader').remove();
    var message = '';
    switch(question_childs[id].type){
      case 'Pertanyaan':
            message = question_childs[id].description;
            write(message,question_childs[id].id);
            userchild(id);
            break;
      case 'Informasi' :
            message = question_childs[id].description_information;
            write(message,question_childs[id].id);
            setTimeout(function() {
              start++;
              loader();
            }, 1000);
            break;
        case 'Informasi Dan Pertanyaan' :
            if(actions[question_childs[id].id]){
              message = question_childs[id].description_information;
              switch(question_childs[id].answer_type){
                case 'checkbox':
                        var answerbefore = [];
                        $.each(actions[question_childs[id].id],function(){
                          answerbefore.push(this.answer.description);
                        })
                        message = message.replace('[answer]',`<b>${answerbefore.join(',')}</b>`);
                        message = message.replace('[question]',`<b>${question_childs[id].description}</b>`);
                        message = message.replace('[update]',`<a onclick="reset(${question_childs[id].id})" style="cursor:pointer">[Disini]</a>`);
                      break;
                case 'radio':
                      message = message.replace('[answer]',`<b>${actions[question_childs[id].id][0].answer.description}</b>`);
                      message = message.replace('[question]',`<b>${question_childs[id].description}</b>`);
                      message = message.replace('[update]',`<a onclick="reset(${question_childs[id].id})" style="cursor:pointer">[Disini]</a>`);
                      break;
                case  'text':
                      message = message.replace('[answer]',`<b>${actions[question_childs[id].id][0].description}</b>`);
                      message = message.replace('[question]',`<b>${question_childs[id].description}</b>`);
                      message = message.replace('[update]',`<a onclick="reset(${question_childs[id].id})" style="cursor:pointer">[Disini]</a>`);
                      break;
                case  'number':
                      message = message.replace('[answer]',`<b>${actions[question_childs[id].id][0].description}</b>`);
                      message = message.replace('[question]',`<b>${question_childs[id].description}</b>`);
                      message = message.replace('[update]',`<a onclick="reset(${question_childs[id].id})" style="cursor:pointer">[Disini]</a>`);
                      break;
              }
              write(message,question_childs[id].id);
              userchild(id,1);
            }
            else{
              message = question_childs[id].description;
              write(message,question_childs[id].id);
              userchild(id);
            }
            break;
      default:
            message = question_childs[id].description;
            write(message,question_childs[id].id);
            
    }
  }
  function botchildreset(id,next){
    var message = '';
    if(!$('#question_'+question_childs[id].id).length){
      switch(question_childs[id].type){
        case 'Pertanyaan':
              message = question_childs[id].description;
              writenext(message,question_childs[id].id,next);
              userchildreset(id,question_childs[id].id);
              break;
        case 'Informasi' :
              message = question_childs[id].description_information;
              writenext(message,question_childs[id].id,next);
              break;
        case 'Informasi Dan Pertanyaan' :
            message = question_childs[id].description;
              writenext(message,question_childs[id].id,next);
              userchildreset(id,question_childs[id].id);
              break;
        default:
              message = question_childs[id].description;
              writenext(message,question_childs[id].id,next);
              
      }
    }
  }
  function write(message,id){
    $('.direct-chat-messages').append(`
      <div class="direct-chat-msg question" id="question_${id}">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
        </div>
        <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
        <div class="direct-chat-text">
          ${message}
        </div>
      </div>
    `);
  }
  function writenext(message,id,next){
    $('#answerdesc_'+next).after(`
      <div class="direct-chat-msg question" id="question_${id}">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
        </div>
        <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
        <div class="direct-chat-text">
          ${message}
        </div>
      </div>
    `);
  }
  function user(next = 0){
    var message = '';
    var chatclass = '';
    switch(questions[start].answer_type){
      case 'checkbox':
            $.each(answers[questions[start].id], function(i, answer) {
              var checked = '';
              $.each(actions[questions[start].id],function(j , action){
                  if(action.assessment_answer_id == answer.id){
                    checked = 'checked';
                  }
              })
              message += `<input type="checkbox" name="answer_choice_${questions[start].id}[]" value="${answer.id}" data-reset="0" ${checked}>
                        ${answer.description} <br/>`;   
            });
            message += `<button type="button" class="btn btn-block btn-default btn-sm" onclick="answer(${questions[start].id})"><i class="fa fa-check"></i></button>`;
            chatclass = 'float-right';
           
            break;
      case 'radio':
        $.each(answers[questions[start].id], function(i, answer) {
          var checked = '';
          $.each(actions[questions[start].id],function(j , action){
              if(action.assessment_answer_id == answer.id){
                checked = 'checked';
              }
          })
          message += `<input type="radio" name="answer_choice_${questions[start].id}" value="${answer.id}" onclick="answer(${questions[start].id})" data-reset="0" ${next==1?checked:''}>
                    ${answer.description} <br/>`;  
          chatclass = 'float-right'; 
        });
        break;
        case 'text':
          message += `<div class="input-group"><input type="text" name="answer_choice_${questions[start].id}" value="${actions[questions[start].id]?actions[questions[start].id][0].description:''}" class="form-control" placeholder="........."  data-reset="0"><span class="input-group-addon" onclick="answer(${questions[start].id})" style="cursor:pointer"><i class="fa fa-check"></i></span></div>`; 
          chatclass = 'form-inline';
          break;
        case 'number':
          message += `<div class="input-group"><input type="text" name="answer_choice_${questions[start].id}" value="${actions[questions[start].id]?actions[questions[start].id][0].description:''}" class="form-control numberfield" placeholder="........."  data-reset="0"><span class="input-group-addon" onclick="answer(${questions[start].id})" style="cursor:pointer"><i class="fa fa-check"></i></span></div>`;
          chatclass = 'form-inline'; 
        break;
    }
    $('.direct-chat-messages').append(`
        <div class="direct-chat-msg right answer" id="answer_${questions[start].id}">
          <div class="direct-chat-info clearfix">
            <span class="direct-chat-name float-right">{{$employee->name}}</span>
          </div>
          <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
          <div class="direct-chat-text ${chatclass}">
            ${message}
          </div>
        </div>
    `);
    if(next == 1){
      answer(questions[start].id,1);
    }
    $(".numberfield").inputmask('decimal', {
      rightAlign: true
    });
    $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
  }
  function userchild(id,next = 0){
    var message = '';
    var chatclass = '';
    switch(question_childs[id].answer_type){
      case 'checkbox':
      
            $.each(answers[question_childs[id].id], function(i, answer) {
              var checked = '';
              $.each(actions[question_childs[id].id],function(j , action){
                  if(action.assessment_answer_id == answer.id){
                    checked = 'checked';
                  }
              })
              message += `<input type="checkbox" name="answer_choice_${question_childs[id].id}[]" value="${answer.id}"  data-reset="0" ${checked}>
                        ${answer.description} <br/>`;   
            });
            message += `<button type="button" class="btn btn-block btn-default btn-sm" onclick="answer(${question_childs[id].id})"><i class="fa fa-check"></i></button>`;
            chatclass = 'float-right';
            break;
      case 'radio':
        $.each(answers[question_childs[id].id], function(i, answer) {
          var checked = '';
          $.each(actions[question_childs[id].id],function(j , action){
              if(action.assessment_answer_id == answer.id){
                checked = 'checked';
              }
          })
          message += `<input type="radio" name="answer_choice_${question_childs[id].id}" value="${answer.id}" onclick="answer(${question_childs[id].id})" data-reset="0" ${next==1?checked:''}>
                    ${answer.description} <br/>`; 
          chatclass = 'float-right';  
        });
        break;
      case 'text':
          message += `<div class="input-group"><input type="text" name="answer_choice_${question_childs[id].id}" value="${actions[question_childs[id].id]?actions[question_childs[id].id][0].description:''}" class="form-control" placeholder="........."  data-reset="0"><span class="input-group-addon" onclick="answer(${question_childs[id].id})" style="cursor:pointer"><i class="fa fa-check"></i></span></div>`; 
          chatclass = 'form-inline'; 
          break;
      case 'number':
           message += `<div class="input-group"><input type="text" name="answer_choice_${question_childs[id].id}" value="${actions[question_childs[id].id]?actions[question_childs[id].id][0].description:''}" class="form-control numberfield" placeholder="........."  data-reset="0"><span class="input-group-addon" onclick="answer(${question_childs[id].id})" style="cursor:pointer"><i class="fa fa-check"></i></span></div>`; 
           chatclass = 'form-inline'; 
        break;
    }
    $('.direct-chat-messages').append(`
        <div class="direct-chat-msg right answer" id="answer_${question_childs[id].id}">
          <div class="direct-chat-info clearfix">
            <span class="direct-chat-name float-right">{{$employee->name}}</span>
          </div>
          <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
          <div class="direct-chat-text ${chatclass}">
            ${message}
          </div>
        </div>
      `);
    if(next == 1){
      answer(question_childs[id].id,1)
    }
    $(".numberfield").inputmask('decimal', {
      rightAlign: true
    });
    $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
  }
  function userchildreset(id,next){
    var message = '';
    var chatclass = '';
    switch(question_childs[id].answer_type){
      case 'checkbox':
            $.each(answers[question_childs[id].id], function(i, answer) {
              message += `<input type="checkbox" name="answer_choice_${question_childs[id].id}[]" value="${answer.id}"  data-reset="0">
                        ${answer.description} <br/>`;   
            });
            message += `<button type="button" class="btn btn-block btn-default btn-sm" onclick="answerreset(${question_childs[id].id})"><i class="fa fa-check"></i></button>`;
            chatclass = 'float-right';
            break;
      case 'radio':
        $.each(answers[question_childs[id].id], function(i, answer) {
          message += `<input type="radio" name="answer_choice_${question_childs[id].id}" value="${answer.id}" onclick="answerreset(${question_childs[id].id})" data-reset="0">
                    ${answer.description} <br/>`; 
          chatclass = 'float-right';  
        });
        break;
      case 'text':
          message += `<div class="input-group"><input type="text" name="answer_choice_${question_childs[id].id}" value="" class="form-control" placeholder="........."  data-reset="0"><span class="input-group-addon" onclick="answerreset(${question_childs[id].id})" style="cursor:pointer"><i class="fa fa-check"></i></span></div>`; 
          chatclass = 'form-inline'; 
          break;
      case 'number':
           message += `<div class="input-group"><input type="text" name="answer_choice_${question_childs[id].id}" value="" class="form-control numberfield" placeholder="........."  data-reset="0"><span class="input-group-addon" onclick="answerreset(${question_childs[id].id})" style="cursor:pointer"><i class="fa fa-check"></i></span></div>`; 
           chatclass = 'form-inline'; 
        break;
    }
    $('#question_'+next).after(`
        <div class="direct-chat-msg right answer" id="answer_${question_childs[id].id}">
          <div class="direct-chat-info clearfix">
            <span class="direct-chat-name float-right">{{$employee->name}}</span>
          </div>
          <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
          <div class="direct-chat-text ${chatclass}">
            ${message}
          </div>
        </div>
      `);
    
    $(".numberfield").inputmask('decimal', {
      rightAlign: true
    });
  }
  function answer(id,next = 0){
    var isreset = 0;
    switch(kpis[id].answer_type){
      case 'checkbox':
          var answerusers = $('input[name^=answer_choice_'+id+']:checked').map(function(){
            return this.value;
          }).get();
          if(answerusers.length > 0){
            var answerdesc = [];
            var answeruser = 0;
            $.each(answerusers, function( index, value ) {
              if(question_childs[value]){
                answeruser = value; 
              }
              answerdesc.push(answer_questions[value].description);
            });
            answerdescription = answerdesc.join(',');
          }
          else{
            answerdescription = 'Tidak ada opsi yang dipilih';
          }
          isreset = $('input[name^=answer_choice_'+id+']').attr('data-reset');
          break;
      case 'radio':
          var answeruser = $('input[name=answer_choice_'+id+']:checked').val();
          answerdescription = answer_questions[answeruser].description;
          isreset = $('input[name^=answer_choice_'+id+']').attr('data-reset');
          break;
      case 'text':
          answerdescription = $('input[name=answer_choice_'+id+']').val();
          if(answerdescription == ''){
            $.gritter.add({
                title: 'Warning!',
                text: 'Jawaban tidak boleh kosong',
                class_name: 'gritter-warning',
                time: 1000,
            });
            return;
          }
          isreset = $('input[name^=answer_choice_'+id+']').attr('data-reset');
          break;
      case 'number':
          answerdescription = $('input[name=answer_choice_'+id+']').val();
          if(answerdescription == ''){
          $.gritter.add({
                title: 'Warning!',
                text: 'Jawaban tidak boleh kosong',
                class_name: 'gritter-warning',
                time: 1000,
            });
            return;
          }
          isreset = $('input[name^=answer_choice_'+id+']').attr('data-reset');
          break;
    }
    if(isreset == 0){
        $('#answer_'+id).hide();
        $('.direct-chat-messages').append(`
          <div class="direct-chat-msg right answerdesc" id="answerdesc_${id}">
            <div class="direct-chat-info clearfix">
              <span class="direct-chat-name float-right">{{$employee->name}}</span>
            </div>
            <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
            <div class="direct-chat-text float-right" style="cursor:pointer" onclick="reset(${id})">
              ${answerdescription}
            </div>
          </div>
        `);
        if(next == 1){
          $('#answerdesc_'+id).hide();
        }
        if(question_childs[answeruser]){
            botchild(answeruser);
        }
        else{
          start++;
          loader();
        }
    }
    else{
      $('#answer_'+id).hide();
      $('#answerdesc_'+id).find('.direct-chat-text').html(answerdescription);
      $('#answerdesc_'+id).show();
      var havechild = null;
      $.each(kpis,function(){
          if(this.question_parent_code == id){
            havechild = this;
          }
      });
      
      if(question_childs[answeruser]){
          botchildreset(answeruser,id);
      }
      else{
        if(havechild){
          $('#question_'+havechild.id).remove();
          $('#answer_'+havechild.id).remove();
          $('#answerdesc_'+havechild.id).remove();
        }
        if(questions[start].id == id){
          start++;
          loader();
        }
      }
    }
    
  }
  function answerreset(id){
    var isreset = 0;
    switch(kpis[id].answer_type){
      case 'checkbox':
          var answerusers = $('input[name^=answer_choice_'+id+']:checked').map(function(){
            return this.value;
          }).get();
          if(answerusers.length > 0){
            var answerdesc = [];
            var answeruser = 0;
            $.each(answerusers, function( index, value ) {
              if(question_childs[value]){
                answeruser = value; 
              }
              answerdesc.push(answer_questions[value].description);
            });
            answerdescription = answerdesc.join(',');
          }
          else{
            answerdescription = 'Tidak ada opsi yang dipilih';
          }
          isreset = $('input[name^=answer_choice_'+id+']').attr('data-reset');
          break;
      case 'radio':
          var answeruser = $('input[name=answer_choice_'+id+']:checked').val();
          answerdescription = answer_questions[answeruser].description;
          isreset = $('input[name^=answer_choice_'+id+']').attr('data-reset');
          break;
      case 'text':
          answerdescription = $('input[name=answer_choice_'+id+']').val();
          if(answerdescription == ''){
            $.gritter.add({
                title: 'Warning!',
                text: 'Jawaban tidak boleh kosong',
                class_name: 'gritter-warning',
                time: 1000,
            });
            return;
          }
          isreset = $('input[name^=answer_choice_'+id+']').attr('data-reset');
          break;
      case 'number':
          answerdescription = $('input[name=answer_choice_'+id+']').val();
          if(answerdescription == ''){
          $.gritter.add({
                title: 'Warning!',
                text: 'Jawaban tidak boleh kosong',
                class_name: 'gritter-warning',
                time: 1000,
            });
            return;
          }
          isreset = $('input[name^=answer_choice_'+id+']').attr('data-reset');
          break;
    }
    $('#answer_'+id).hide();
    $('#answer_'+id).after(`
      <div class="direct-chat-msg right answerdesc" id="answerdesc_${id}">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name float-right">{{$employee->name}}</span>
        </div>
        <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
        <div class="direct-chat-text float-right" style="cursor:pointer" onclick="reset(${id})">
          ${answerdescription}
        </div>
      </div>
    `);
    
  }
  function reset(id){
    if(kpis[id].type == 'Pertanyaan'){
      if(actions[kpis[id].id]){
        $.gritter.add({
            title: 'Warning!',
            text: 'Jawaban tidak dapat diubah',
            class_name: 'gritter-warning',
            time: 1000,
        });
      }
      else{
        $('input[name^=answer_choice_'+id+']').attr('data-reset',1);
        $('#answer_'+id).show();
        $('#answerdesc_'+id).hide();
      }
    }
    else{
      $('input[name^=answer_choice_'+id+']').attr('data-reset',1);
      $('#answer_'+id).show();
      $('#answerdesc_'+id).hide();
    }
  }
  function finish(e){
    if(e.value == 1){
      $('#finish_answer').hide();
      $('.direct-chat-messages').append(`
      <div class="direct-chat-msg right">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name float-right">{{$employee->name}}</span>
        </div>
        <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
        <div class="direct-chat-text float-right">
          Ya
        </div>
      </div>
    `);
      $('.direct-chat-messages').append(`
          <div class="direct-chat-msg loader">
            <div class="direct-chat-info clearfix">
              <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
            </div>
            <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
            <div class="direct-chat-text">
               Terimakasih data  kpi anda sedang disimpan.
            </div>
          </div>
        `);
      $('#form').submit();
    }
    else{
      $('#finish_answer').hide();
      $('.direct-chat-messages').append(`
      <div class="direct-chat-msg right">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name float-right">{{$employee->name}}</span>
        </div>
        <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
        <div class="direct-chat-text float-right">
          Muat Ulang
        </div>
      </div>
    `);
      $('.direct-chat-messages').append(`
          <div class="direct-chat-msg loader">
            <div class="direct-chat-info clearfix">
              <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
            </div>
            <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
            <div class="direct-chat-text">
               Pertanyaan akan diatur ulang beberapa detik lagi.
            </div>
          </div>
        `);
        setTimeout(function() {
          start = 0;
          $('.direct-chat-messages').empty();
          loader();
        }, 2000);
    }

    $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
  }
  function reload(e){
    if(e.value == 1){
      $('#error_answer').remove();
      $('.direct-chat-messages').append(`
      <div class="direct-chat-msg right">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name float-right">{{$employee->name}}</span>
        </div>
        <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
        <div class="direct-chat-text float-right">
          Ya
        </div>
      </div>
    `);
      loader();
    }
    else{
      $('#error_answer').remove();
      $('.direct-chat-messages').append(`
      <div class="direct-chat-msg right">
        <div class="direct-chat-info clearfix">
          <span class="direct-chat-name float-right">{{$employee->name}}</span>
        </div>
        <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
        <div class="direct-chat-text float-right">
          Muat Ulang
        </div>
      </div>
    `);
      $('.direct-chat-messages').append(`
          <div class="direct-chat-msg loader">
            <div class="direct-chat-info clearfix">
              <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
            </div>
            <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
            <div class="direct-chat-text">
               Pertanyaan akan diatur ulang beberapa detik lagi.
            </div>
          </div>
        `);
        setTimeout(function() {
          start = 0;
          $('.direct-chat-messages').empty();
          loader();
        }, 2000);
    }

    $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
  }
  $(document).ready(function(){
    loader();
    $(document).on('keypress','input',function(event){
      var keycode = (event.keyCode ? event.keyCode : event.which);
      if(keycode == '13'){
        event.preventDefault()
      }
    });
    $("#form").validate({
      errorElement: 'span',
      errorClass: 'help-block',
      focusInvalid: false,
      highlight: function (e) {
        $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
      },
  
      success: function (e) {
        $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
        $(e).remove();
      },
      errorPlacement: function (error, element) {
        if(element.is(':file')) {
          error.insertAfter(element.parent().parent().parent());
        }else
        if(element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } 
        else
        if (element.attr('type') == 'checkbox') {
          error.insertAfter(element.parent());
        }
        else{
          error.insertAfter(element);
        }
      },
      submitHandler: function() { 
        if(start != questions.length){
          $.gritter.add({
              title: 'Warning!',
              text: 'Silahkan lengkapi isian semua jawaban terlebih dahulu',
              class_name: 'gritter-warning',
              time: 1000,
          });
        }
        var record = [];
        $(".direct-chat-msg").each(function() {
          
          if($(this).hasClass('question')){
            record.push({
              'id'    : $(this).attr('id'),
              'class' : $(this).attr('class'),
              'style' : $(this).attr('style'),
              'text'  : $(this).html(),
            })
          }
          if($(this).hasClass('answer')){
            record.push({
              'id'    : $(this).attr('id'),
              'class' : $(this).attr('class'),
              'style' : $(this).attr('style'),
              'text'  : $(this).html()
            })
          }
          if($(this).hasClass('answerdesc')){
            record.push({
              'id'    : $(this).attr('id'),
              'class' : $(this).attr('class'),
              'style' : $(this).attr('style'),
              'text'  : $(this).html()
            })
          }
          if($(this).hasClass('assessmentresult')){
            record.push({
              'id'    : $(this).attr('id'),
              'class' : $(this).attr('class'),
              'style' : $(this).attr('style'),
              'text'  : $(this).html()
            })
          }
        });
        var data = new FormData($('#form')[0]);
        data.append('record',JSON.stringify(record));
        $.ajax({
          url:$('#form').attr('action'),
          method:'post',
          data: data,
          processData: false,
          contentType: false,
          dataType: 'json', 
          beforeSend:function(){
              $('.overlay').removeClass('hidden');
          }
        }).done(function(response){
              $('.overlay').addClass('hidden');
              $('#error_answer').remove();
              if(response.status){
                document.location = response.results;
              }
              else{	
                $('.direct-chat-messages').append(`
                <div class="direct-chat-msg error" >
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
                  </div>
                  <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
                  <div class="direct-chat-text">
                    Maaf {{config('configs.bot_username')}} sedang mengalami gangguan :( <br/> Apakah anda mau melanjutkan pengisian ?
                    </div>
                </div>
              `);
            $('.direct-chat-messages').append(`
              <div class="direct-chat-msg right" id="error_answer">
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name float-right">{{$employee->name}}</span>
                </div>
                <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
                <div class="direct-chat-text float-right">
                  <input type="radio" value="1" onclick="finish(this)">
                  Ya <br/><input type="radio" value="0" onclick="finish(this)"> Muat Ulang
                </div>
              </div>`);

            $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
              }
              return;
        }).fail(function(response){
            $('.overlay').addClass('hidden');
            $('#error_answer').remove();
            $('.direct-chat-messages').append(`
                <div class="direct-chat-msg error" >
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{{config('configs.bot_username')}}</span>
                  </div>
                  <img class="direct-chat-img" src="{{is_file(config('configs.bot_icon'))?asset(config('configs.bot_icon')):asset('assets/bot.png')}}" alt="{{config('configs.bot_username')}}">
                  <div class="direct-chat-text">
                    Maaf {{config('configs.bot_username')}} sedang mengalami gangguan :( <br/> Apakah anda mau mengirimkan lagi ?
                    </div>
                </div>
              `);
            $('.direct-chat-messages').append(`
              <div class="direct-chat-msg right" id="error_answer">
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name float-right">{{$employee->name}}</span>
                </div>
                <img class="direct-chat-img" src="{{is_file('assets/user/'.Auth::guard('admin')->user()->id.'.png')?asset('assets/user/'.Auth::guard('admin')->user()->id.'.png'):asset('adminlte/images/user2-160x160.jpg')}}" alt="{{$employee->name}}">
                <div class="direct-chat-text float-right">
                  <input type="radio" value="1" onclick="finish(this)">
                  Ya <br/><input type="radio" value="0" onclick="finish(this)"> Muat Ulang
                </div>
              </div>`);

            $(".direct-chat-messages").stop().animate({ scrollTop: $(".direct-chat-messages")[0].scrollHeight}, 1000); 
        })		
      }
    });
  });
</script>
@endpush