<div class="alert @if(session('prompt.status')) alert-success @else alert-danger @endif">
 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
 <strong>
  <i class="fa @if(session('prompt.status')) fa-check-circle @else fa-close @endif fa-lg fa-fw"></i> 提示:
 </strong>
  {{ session('prompt.msg') }}
</div>