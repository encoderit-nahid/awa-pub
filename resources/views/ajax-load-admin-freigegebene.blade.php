                      @foreach($projects as $project)
                        <br>

                      @if($project->jury == 0)
                      <button class="btn btn-primary action-add-jury" id="{{$project->id}}">OK + Jury</button>
                      @else
                      <button class="btn btn-primary action-remove-jury" id="{{$project->id}}">Remove Jury</button>
                      @endif
                      <br>
                      <br>
{{--                         <button class="btn btn-primary action-accept" id="{{$project->id}}">OK</button>
                        <button class="btn btn-primary action-jury" id="{{$project->id}}">OK + Jury</button>
                        <button class="btn btn-primary action-reject" id="{{$project->id}}">Mängel</button>
                        <button class="btn btn-primary action-delete" id="{{$project->id}}">Löschen</button>
						<button type="submit" class="btn btn-primary" value = "change" name="submit">{{ __('Ändern') }}</button>
                        <br>
                        <br> --}}



              <p style=""><b>Kategorie: {{ $project->cat_name }}
                          <p style=""><b>Projektname:  {{ $project->projektname }} ID: {{ $project->id }}</b></p>
                          <p style=""><b>Datum:  {{ $project->datum }} Ort: {{ $project->ort }}</b></p>
                          {{-- <p style=""><b>Copyright:  {{ $project->copyright }}</b></p> --}}
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
						  {{-- @if($project->extra !="")
						  <br>
						  <div class="form-group">
                              <label for="comment">Extras: </label>
                              <textarea class="form-control" rows="5" id="comment" readonly>{{$project->extra}} {{$project->ort}} - {{$project->datum}}
                              </textarea>
                          </div>
						  @endif --}}
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

                      		  <div class="column" id = "thumb-<?php echo md5($image->filename)?>">
                      		    <img src="{{ url($thumb_url) }}" alt="{{$image->filename}}" style="width:70%;height:70%" onclick="openModal('{{$project->projektname}}');currentSlide(<?php echo $imageCount ?> , '<?php echo $project->projektname?>')" class="hover-shadow cursor">
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
