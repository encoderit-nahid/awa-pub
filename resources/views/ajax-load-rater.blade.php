                      @foreach($projects as $project)
                          <p style=""><b>Kategorie: {{ $project->cat_name }}
                          <p style=""><b>Projektname:  {{ $project->projektname }} ID: {{ $project->id }}</b></p>
                          @if ( $project->stat === 0 )
                            <p style=""><b>Projektstatus: eingereicht</b></p>
                          @elseif ( $project->stat === 2 )
                            <p style=""><b>Projektstatus: freigegeben</b></p>
                          @elseif ($project->stat === 3 )
                            <p style=""><b>Projektstatus: zurückgewiesen</b></p>
                          @endif

                          <div class="form-group">
                              <label for="comment">Projektinfos:</label>
                              <textarea class="form-control" rows="5" id="comment">
Projektname: {{$project->projektname}}
Projectbeschreibung: {{$project->beschreibung }}
                              </textarea>
                          </div>
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


                          <form method="POST" action="{{ route('project-rated') }}">
                            @csrf
                            {{ Form::hidden('project_id', $project->id) }}
                              <label for="Cat"></label>
                              <select class="form-control" name="counts" id="counts" data-parsley-required="true" onchange='this.form.submit()'>
                                <option value="0">0</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                                <option value="60">60</option>
                                <option value="70">70</option>
                                <option value="80">80</option>
                                <option value="90">90</option>
                                <option value="100">100</option>
                              </select>
                          </form>

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
