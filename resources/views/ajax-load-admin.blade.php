                      @foreach($projects as $project)
                        <br>

                      <button class="btn btn-primary action-accept" id="{{$project->id}}">OK</button>
                      @if($project->inv == 0 && $project->jury == 0)
                        <button class="btn btn-primary action-inv" id="{{$project->id}}">OK + Rechnung</button>
                      @endif
                      @if($project->inv == 1 && $project->jury == 0)
                        <button class="btn btn-primary action-jury" id="{{$project->id}}">OK + Jury</button>
                      @endif
                      <button class="btn btn-primary action-reject" id="{{$project->id}}">Mängel</button>
                      <button class="btn btn-primary action-delete" id="{{$project->id}}">Löschen</button>
                      @if($project->free==0)
                      <button class="btn btn-primary action-free" dataValue="1" id="{{$project->id}}">Gratis Projekt</button>
                      @else
                      <button class="btn btn-primary action-free" dataValue="0" id="{{$project->id}}">Nicht mehr Gratis</button>
                      @endif

                      @if($project->service==0)
                      <button class="btn btn-primary action-service" dataValue="1" id="{{$project->id}}">UploadService (Extra Kosten)</button>
                      @else
                      <button class="btn btn-primary action-service" dataValue="0" id="{{$project->id}}">Kein UploadService (Extra Kosten)</button>
                      @endif

                      @if($project->special==0)
{{--                      <button class="btn btn-primary action-special" dataValue="1" id="{{$project->id}}">Spezialprojekt</button>--}}
                      @else
                      <button class="btn btn-primary action-special" dataValue="0" id="{{$project->id}}">Kein Spezialprojekt mehr</button>
                      @endif
                      <a href="{{ url('/project/edit-image/'.$project->id.'/'.$project->cat_id) }}" class="btn btn-primary" >Bild(er) ändern</a>
                      <form method="POST" action="{{ route('project-change') }}" style="float: left; margin-right: 5px;">
                          @csrf
                          {{ Form::hidden('projectID', $project->id) }}
                          {{ Form::hidden('catID', $project->cat_id) }}
                          <button type="submit" class="btn btn-primary" value = "change" name="submit">
                         {{ __('Ändern') }}
                        </button>
                      </form>
                      <br>
                      <br>
{{--                         <button class="btn btn-primary action-accept" id="{{$project->id}}">OK</button>
                        <button class="btn btn-primary action-jury" id="{{$project->id}}">OK + Jury</button>
                        <button class="btn btn-primary action-reject" id="{{$project->id}}">Mängel</button>
                        <button class="btn btn-primary action-delete" id="{{$project->id}}">Delete</button>
						<button type="submit" class="btn btn-primary" value = "change" name="submit">{{ __('Ändern') }}</button>
                        <br>
                        <br> --}}


                          <p style=""><b>Name: {{ $users[$project->user_id]['anr'] }} {{ $users[$project->user_id]['vorname'] }} {{ $users[$project->user_id]['name'] }} - {{ $users[$project->user_id]['firma'] }} </b></p>
              <p style=""><b>Kategorie: {{ $project->cat_name }}
                          <p style=""><b>Projektname:  {{ $project->projektname }} ID: {{ $project->id }}</b></p>
                          <p style=""><b>Datum:  {{ $project->datum }} Ort: {{ $project->ort }}</b></p>
                          @if ( $project->stat === 0 )
                            @if ($project->stat == 0 && $project->is_selected_for_first_evaluation)
                                <p style=""><b>Projektstatus: Zur Bewertung freigegeben</b></p>
                            @else
                                <p style=""><b>Projektstatus: abgespeichert</b></p>
                            @endif
                          @elseif ( $project->stat === 2 )
                            @if ($project->jury == 0 && $project->inv == 0)
                              <p style=""><b>Projektstatus: Für Rechnung freigegeben</b></p>
                            @elseif ($project->jury == 0 && $project->inv == 1)
                              <p style=""><b>Projektstatus: Zur Rechnungslegung freigeben</b></p>
                            @elseif ($project->jury == 1)
                              <p style=""><b>Projektstatus: Zur Bewertung freigegeben</b></p>
                            @endif
                          @elseif ($project->stat === 3 )
                            <p style=""><b>Projektstatus: zurückgewiesen</b></p>
                          @endif

                          @if($project->youtube !="")
                          <!--  <p style=""> <button link="{{ $project->youtube }}" class="btn btn-primary youtube-btn">Video ansehen</button> </p> -->
                          <!-- <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="{{ $project->youtube .'?h=60b811dbb0&amp;badge=0&amp;autopause=0&amp' }};autopause=0&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:50%;height:50%;" title="Wedding Award Germany"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script> -->
                            <h3><a href="{{$project->youtube}}" class="btn btn-primary">View Video</a></h3>
                            <!-- dropbox -->
                            <!-- <iframe src="{{$project->youtube}}" height="280px" width="640px" allowfullscreen></iframe> -->
                          @endif
                          <br>
						  @if($project->beschreibung !="")
                          <div class="form-group">
                              <label for="comment">Projektinfos:</label>
                              <textarea class="form-control" rows="5" id="comment" readonly>{{$project->beschreibung }}
                              </textarea>
                          </div>
						  @endif
						  @if($project->testimonial !="")
						  <br>
						  <div class="form-group">
                              <label for="comment">Referenzen:</label>
                              <textarea class="form-control" rows="5" id="comment" readonly>{{$project->testimonial}}
                              </textarea>
                          </div>
						  @endif
						  @if($project->extra !="")
						  <br>
						  <div class="form-group">
                              <label for="comment">Extras: </label>
                              <textarea class="form-control" rows="5" id="comment" readonly>{{$project->extra}} {{$project->ort}} - {{$project->datum}}
                              </textarea>
                          </div>
						  @endif
                          <br>
                          <div class="row">
                            <?php $imageCount = 0;?>
                          @foreach($project->images as $image)

                            @php
                              $imageCount ++;
                              $thumb_url = $image->thumb_url;
                              if(substr( $thumb_url , 0, 1) != "/") {
                                $thumb_url = '/'.$thumb_url;
                              }
                              $filename = $image->filename;
                              if(substr( $filename , 0, 1) != "/") {
                                $filename = '/'.$filename;
                              }
                              $url = $image->url;
                              if(substr( $url , 0, 1) != "/") {
                                $url = '/'.$url;
                              }

                            @endphp

                            <div class="column" id = "thumb-<?php echo md5($filename)?>">
                              <img src="{{ url($thumb_url) }}" alt="{{$filename}}" style="width:70%;height:70%" onclick="openModal('{{$project->projektname}}');currentSlide(<?php echo $imageCount ?> , '<?php echo $project->projektname?>')" class="hover-shadow cursor">
                            </div>

                          @endforeach

                          </div>
                          <br>


                          {{-- <form method="POST" action="{{ route('project-freigegeben') }}">
                              @csrf
                                {{ Form::hidden('project_id', $project->id) }}
                                <label for="Cat"></label>
                                    <select class="form-control" name="counts" id="counts" data-parsley-required="true" onchange='this.form.submit()'>
                                      <option value="2">Freigeben</option>
                                      <option value="3">Zurückweisen</option>
                                      <option value="1">Löschen</option>
                                    </select>
                              </form> --}}

                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                          <div id="myModal-{{$project->projektname}}" class="modal modal modal-height-width en-p-20">
                            <span class="close cursor en-fs-30" onclick="closeModal('{{$project->projektname}}')">&times;</span>
                            <div class="modal-content p-relative en-p-40 w-auto-h-100">
                              <div class = "wide_wrapper text-center big-slider-image-container w-auto-h-100" >
                                @foreach($project->images as $image)
                                  @php
                                    $thumb_url = $image->thumb_url;
                                    if(substr( $thumb_url , 0, 1) != "/") {
                                      $thumb_url = '/'.$thumb_url;
                                    }
                                    $filename = $image->filename;
                                    if(substr( $filename , 0, 1) != "/") {
                                      $filename = '/'.$filename;
                                    }
                                    $url = $image->url;
                                    if(substr( $url , 0, 1) != "/") {
                                      $url = '/'.$url;
                                    }

                                  @endphp
                                <div class="mySlides-<?php echo $project->projektname ?> w-auto-h-100" data-responsive="true" id = "wide-<?php echo md5($image->filename) ?>">

                                  <img src="{{ url($url) }}" class="big-slider-image img-responsive w-auto-h-100 en-m-auto" alt="Nature and sunrise">
                                </div>
                                @endforeach

                              </div>
                              <a class="prev en-fs-20" onclick="plusSlides(-1 , '<?php echo $project->projektname ?>')">&#10094;</a>
                              <a class="next en-fs-20" onclick="plusSlides(1 , '<?php echo $project->projektname ?>')">&#10095;</a>

                              <div style = "height : 30px;"></div>

                              <div class = "clearfix">
                                <?php $imageCount = 0; ?>
                                @foreach($project->images as $image)
                                @php
                                  $imageCount ++;
                                  $thumb_url = $image->thumb_url;
                                  if(substr( $thumb_url , 0, 1) != "/") {
                                    $thumb_url = '/'.$thumb_url;
                                  }
                                  $filename = $image->filename;
                                  if(substr( $filename , 0, 1) != "/") {
                                    $filename = '/'.$filename;
                                  }
                                  $url = $image->url;
                                  if(substr( $url , 0, 1) != "/") {
                                    $url = '/'.$url;
                                  }

                                @endphp
                                @if ($user->rolle === 0)
                                <div class="column clearfix" id = "slide-<?php echo md5($image->filename) ?>">
                                  <div class = "clearfix text-center" style = "background : grey">

                                  </div>
                                  <div class = "image-wrapper">
                                    <img id = "slideimg-<?php echo md5($image->filename) ?>" class="demo-<?php echo $project->projektname; ?> cursor" src="{{ url($thumb_url) }}" style="width:100%" onclick="currentSlide(<?php echo $imageCount; ?> , '<?php echo $project->projektname; ?>')" alt="Nature and sunrise">
                                  </div>
                                </div>
                                @endif
                                @endforeach

                              </div>

                            </div>
                            <div style = "height : 80px"></div>
                          </div>
                        </div>
                      </div>


                      <div style="height: 50px;"></div>
                      @endforeach

                      {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> --}}

                      <script type="text/javascript">


                      // $(document).ready(function() {

                      //     var token = $('input[name="ajax_token"]').val();
                      //     // $(window).scroll(fetchtickets);

                      //     function fetchtickets() {

                      //         var page = $('.endless-pagination').data('next-page');
                      //         var doWork = $('#do_work').val();

                      //         if((page !== null) && (doWork=='1')) {

                      //             clearTimeout( $.data( this, "scrollCheck" ) );

                      //             $.data( this, "scrollCheck", setTimeout(function() {
                      //                 var scroll_position_for_tickets_load = $(window).height() + $(window).scrollTop() + 300;
                      //                 $('.ajax-load').show();

                      //                 if(scroll_position_for_tickets_load >= $(document).height()) {
                      //                   page = page.replace("http://", "https://");
                      //                     $.get(page, function(data){
                      //                       $('.ajax-load').hide();
                      //                         $('.projects').append(data.projects);
                      //                         $('.endless-pagination').data('next-page', data.next_page);
                      //                     });
                      //                 }
                      //             }, 1000))

                      //         }else{
                      //           $('.ajax-load').show();
                      //           $('.ajax-load').html('<h2>No more post left</h2>');
                      //         }
                      //     }

                      //     $(document).on("change","#category",function(){
                      //       var url      = '/project-freigeben/'+$(this).val();
                      //       window.location.replace(url);
                      //     });

                      //     $(document).on("click",".action-free",function(){
                      //       var dataValue = $(this).attr('dataValue');
                      //       $(this).attr("disabled", "disabled");
                      //       $.ajax({
                      //           url: "{{ url('/project-free') }}".replace("http://", "https://"),
                      //           type: 'POST',
                      //             headers: {
                      //               'X-CSRF-TOKEN': token
                      //             },
                      //           data: {
                      //               id : $(this).attr('id'),
                      //               dataValue : dataValue
                      //           },
                      //           success: function(response){
                      //             if (response.msg=="Success") {
                      //               alert("The project free status has been changed successfully.");
                      //             }
                      //           },error:function(){
                      //             alert("error!!!!");
                      //           }

                      //       });
                      //     });
                      //     $(document).on("click",".action-service",function(){
                      //       var dataValue = $(this).attr('dataValue');
                      //       $(this).attr("disabled", "disabled");
                      //       $.ajax({
                      //           url: "{{ url('/project-service') }}".replace("http://", "https://"),
                      //           type: 'POST',
                      //             headers: {
                      //               'X-CSRF-TOKEN': token
                      //             },
                      //           data: {
                      //               id : $(this).attr('id'),
                      //               dataValue : dataValue
                      //           },
                      //           success: function(response){
                      //             if (response.msg=="Success") {
                      //               alert("The project service status has been changed successfully.");
                      //             }
                      //           },error:function(){
                      //             alert("error!!!!");
                      //           }

                      //       });
                      //     });

                      //     $(document).on("click",".action-special",function(){
                      //       var dataValue = $(this).attr('dataValue');
                      //       $(this).attr("disabled", "disabled");
                      //       $.ajax({
                      //           url: "{{ url('/project-special') }}".replace("http://", "https://"),
                      //           type: 'POST',
                      //             headers: {
                      //               'X-CSRF-TOKEN': token
                      //             },
                      //           data: {
                      //               id : $(this).attr('id'),
                      //               dataValue : dataValue
                      //           },
                      //           success: function(response){
                      //             if (response.msg=="Success") {
                      //               alert("The project special status has been changed successfully.");
                      //             }
                      //           },error:function(){
                      //             alert("error!!!!");
                      //           }

                      //       });
                      //     });


                      //     // Youtube popuP
                      //     $(document).on("click", ".youtube-btn", function() {
                      //       var link = $(this).attr('link');
                      //       var newarr = link.split('/');
                      //       if(newarr.length == 4){
                      //         var vimeoId = newarr[3];
                      //         showYoutube(vimeoId);
                      //       }else{
                      //         alert('Video Invalid');
                      //       }
                      //     });

                      //     $("#myYoutube").on("hidden.bs.modal",function(){
                      //       $("#iframeYoutube").attr("src","#");
                      //     });

                      //     function showYoutube(id) {
                      //       var src = "//player.vimeo.com/video/"+id;
                      //       // src = src.replace('watch?v=', 'embed/');
                      //       $("#iframeYoutube").attr("src", src);
                      //       $("#myYoutube").modal("show");
                      //       $('.modal-backdrop').css('position', 'relative');
                      //     }

                      //     $(document).on('click', '.action-accept', function(){
                      //       $.ajax({
                      //           url: "{{ url('/project-accept-admin') }}".replace("http://", "https://"),
                      //           type: 'POST',
                      //             headers: {
                      //               'X-CSRF-TOKEN': token
                      //             },
                      //           data: {
                      //               id : $(this).attr('id')
                      //           },
                      //           success: function(response){
                      //             console.log(response);
                      //             // alert("Project has been successfully accepted.");
                      //             location.reload();
                      //           },error:function(){
                      //             alert("error!!!!");
                      //           }

                      //       });
                      //     });

                      //     var deletingId = 0;
                      //     $(document).on('click', '.action-delete', function(){
                      //       deletingId = $(this).attr('id');
                      //       $('#Modal-delete').modal('toggle');
                      //       $('.modal-backdrop').css('position', 'relative');

                      //     });
                      //     $('#model-delete-cancel').click(function(){
                      //       $('#Modal-delete').modal('toggle');
                      //     });
                      //     $(document).on('click', '#model-final-delete', function(){
                      //       $.ajax({
                      //           url: "{{ url('/project-delete-admin') }}".replace("http://", "https://"),
                      //           type: 'POST',
                      //             headers: {
                      //               'X-CSRF-TOKEN': token
                      //             },
                      //           data: {
                      //               id : deletingId
                      //           },
                      //           success: function(response){
                      //             // alert("Project has been successfully accepted.");
                      //             location.reload();
                      //           }
                      //       });
                      //     });

                      //     $(document).on('click', '.action-jury', function(){
                      //       $.ajax({
                      //           url: "{{ url('/project-jury-admin') }}".replace("http://", "https://"),
                      //           type: 'POST',
                      //             headers: {
                      //               'X-CSRF-TOKEN': token
                      //             },
                      //           data: {
                      //               id : $(this).attr('id')
                      //           },
                      //           success: function(response){
                      //             // alert("Project has been successfully accepted.");
                      //             location.reload();
                      //           }
                      //       });
                      //     });

                      //     $(document).on('click', '.action-inv', function(){
                      //       $.ajax({
                      //           url: "{{ url('/project-inv-admin') }}".replace("http://", "https://"),
                      //           type: 'POST',
                      //             headers: {
                      //               'X-CSRF-TOKEN': token
                      //             },
                      //           data: {
                      //               id : $(this).attr('id')
                      //           },
                      //           success: function(response){
                      //             // alert("Project has been successfully accepted.");
                      //             location.reload();
                      //           }
                      //       });
                      //     });


                      //     var rejectionId = 0;
                      //     $(document).on('click', '.action-reject', function(){
                      //       rejectionId = $(this).attr('id');
                      //       //console.log(rejectionId);
                      //       $('#myModal').modal('toggle');
                      //       $('.modal-backdrop').css('position', 'relative');

                      //     });
                      //     $('#model-cancel').click(function(){
                      //       $('#myModal').modal('toggle');
                      //       $('.email-body').val('');
                      //     });

                      //     $('#model-send-email').click(function(){
                      //       $('#myModal').modal('toggle');
                      //       var emailBody = $(".email-body").val();
                      //       $.ajax({
                      //           url: "{{ url('/project-reject-admin') }}",
                      //           type: 'POST',
                      //             headers: {
                      //               'X-CSRF-TOKEN': token
                      //             },
                      //           data: {
                      //               'id': rejectionId,
                      //               'emailBody' : emailBody
                      //           },
                      //           success: function(response){
                      //             //console.log(response);
                      //             // alert("Project has been rejected.");
                      //             location.reload();
                      //           }
                      //       });
                      //     });


                      // });
                      // </script>
                      <script>
                        // function openModal(projectName) {
                        //   document.getElementById('myModal-' + projectName).style.display = "block";
                        // }

                        // function del(imageName , md5){
                        //   var token = $('input[name="ajax_token"]').val();
                        //   $.ajax({
                        //       url: "{{ url('/show-delete') }}".replace("http://", "https://"),
                        //       type: 'POST',
                        //             headers: {
                        //               'X-CSRF-TOKEN': token
                        //             },
                        //       data: {
                        //           fileName : imageName
                        //       },
                        //       success: function(response)
                        //       {
                        //         $('#thumb-'+md5).remove();
                        //         // $('#wide-'+md5).remove();
                        //         $('#slide-'+md5).remove();
                        //       }
                        //   });
                        // }

                        // function closeModal(projectName) {
                        //   document.getElementById('myModal-' + projectName).style.display = "none";
                        // }
                        // var slideIndex = {};
                        // // <?php foreach($projects as $project){ ?>
                        // //   slideIndex["<?php echo $project->projektname ?>"] = 1;
                        // //   showSlides(slideIndex["<?php echo $project->projektname ?>"] , "<?php echo $project->projektname ?>");
                        // // <?php }?>


                        // function plusSlides(n , projectName) {
                        //   showSlides(slideIndex[projectName] += n , projectName);
                        // }

                        // function currentSlide(n , projectName) {
                        //   showSlides(slideIndex[projectName] = n , projectName);
                        // }

                        // function showSlides(n , projectName) {
                        //   var i;
                        //   var slides = document.getElementsByClassName("mySlides-" + projectName);
                        //   var dots = document.getElementsByClassName("demo-" + projectName);

                        //   if (n > slides.length) {slideIndex[projectName] = 1}
                        //   if (n < 1) {slideIndex[projectName] = slides.length}
                        //   for (i = 0; i < slides.length; i++) {
                        //       slides[i].style.display = "none";
                        //   }
                        //   for (i = 0; i < dots.length; i++) {
                        //       dots[i].className = dots[i].className.replace(" active", "");
                        //   }
                        //   slides[slideIndex[projectName]-1].style.display = "block";
                        //   dots[slideIndex[projectName]-1].className += " active";

                        // }
                        </script>
