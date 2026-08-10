@extends('admin.layouts.master')
@section('title', 'About Details - Edit')

@section('content')
<style>
  :root{
    --pink-50:#fdf2f8;
    --pink-100:#fce7f3;
    --pink-200:#fbcfe8;
    --pink-400:#f472b6;
    --pink-500:#ec4899;
    --pink-600:#db2777;
    --pink-700:#be185d;
    --ink:#1e1b2e;
    --muted:#8b8296;
    --border:#f1d9e8;
  }
  *{box-sizing:border-box;}
  html,body{ width:100%; overflow-x:hidden; }
  body{
    margin:0;
    font-family:'Segoe UI',system-ui,-apple-system,sans-serif;
    background:var(--pink-50);
    padding:32px 16px;
    color:var(--ink);
  }
  .card{
    max-width:720px;
    width:100%;
    margin:0 auto;
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 4px 24px rgba(219,39,119,0.08);
    border:1px solid var(--border);
  }
  .header{
    background:var(--pink-100);
    padding:24px 32px;
    border-bottom:1px solid var(--border);
  }
  .header h1{
    margin:0;
    font-size:24px;
    display:flex;
    align-items:center;
    gap:10px;
    color:var(--pink-700);
  }
  .header p{
    margin:6px 0 0;
    color:var(--muted);
    font-size:14px;
  }
  .body{
    padding:32px;
  }
  .alert-success{
    background:#ecfdf5;
    border:1px solid #a7f3d0;
    color:#065f46;
    padding:12px 16px;
    border-radius:10px;
    font-size:14px;
    margin-bottom:20px;
  }
  .error-msg{
    color:#dc2626;
    font-size:12.5px;
    margin-top:6px;
  }
  label{
    display:block;
    font-weight:600;
    font-size:14px;
    margin-bottom:8px;
    color:var(--ink);
  }
  .required{color:var(--pink-600);}
  .section{
    margin-bottom:28px;
  }
  .icons-row{
    display:flex;
    justify-content:center;
    gap:20px;
    flex-wrap:wrap;
  }
  .icon-col{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:10px;
    flex:1 1 0;
    min-width:130px;
    max-width:220px;
  }
  .icon-box{
    width:100%;
    aspect-ratio:4/3;
    border:1px solid var(--border);
    border-radius:12px;
    overflow:hidden;
    background:var(--pink-50);
  }
  .icon-box img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }
  .icon-col .file-label{
    width:100%;
    text-align:center;
    padding:9px 10px;
  }
  .icon-col .file-name{
    font-size:11.5px;
    text-align:center;
  }
  .file-label{
    background:var(--pink-500);
    color:#fff;
    border:none;
    padding:9px 18px;
    border-radius:8px;
    font-weight:600;
    font-size:14px;
    cursor:pointer;
    white-space:nowrap;
    transition:background .15s ease;
  }
  .file-label:hover{ background:var(--pink-600); }
  .file-name{
    font-size:14px;
    color:var(--muted);
  }
  input[type="file"]{ display:none; }
  .hint{
    font-size:12.5px;
    color:var(--muted);
    margin-top:4px;
  }
  input[type="text"], textarea, select{
    width:100%;
    padding:12px 14px;
    border:1px solid var(--border);
    border-radius:10px;
    font-size:14px;
    font-family:inherit;
    color:var(--ink);
    background:#fff;
    outline:none;
    transition:border-color .15s ease, box-shadow .15s ease;
  }
  input[type="text"]:focus, textarea:focus, select:focus{
    border-color:var(--pink-400);
    box-shadow:0 0 0 3px rgba(244,114,182,0.2);
  }
  textarea{
    resize:vertical;
    min-height:90px;
  }
  .footer{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding-top:20px;
    border-top:1px solid var(--border);
  }
  .back-link{
    color:var(--muted);
    text-decoration:underline;
    font-size:14px;
  }
  .save-btn{
    background:var(--pink-600);
    color:#fff;
    border:none;
    padding:12px 26px;
    border-radius:10px;
    font-weight:700;
    font-size:14px;
    cursor:pointer;
    transition:background .15s ease, transform .1s ease;
  }
  .save-btn:hover{ background:var(--pink-700); }
  .save-btn:active{ transform:scale(0.98); }

  @media (max-width:640px){
    .body{ padding:24px; }
    .header{ padding:20px 24px; }
    .header h1{ font-size:21px; }
    .icon-col{ min-width:100px; }
  }

  @media (max-width:480px){
    body{ padding:16px 8px; }
    .card{ border-radius:12px; }
    .body{ padding:18px; }
    .header{ padding:18px 18px; }
    .header h1{ font-size:19px; }
    .icons-row{ gap:12px; }
    .icon-col{ min-width:0; flex:1 1 30%; max-width:none; }
    .icon-col .file-label{ padding:8px 6px; font-size:12px; }
    .icon-col .file-name{ font-size:10px; }
    .footer{
      flex-direction:column-reverse;
      align-items:stretch;
      gap:14px;
    }
    .save-btn{ width:100%; text-align:center; }
    .back-link{ text-align:center; }
  }
</style>

<div class="card">
  <div class="header">
    <h1> Update About Form</h1>
    <p>Keep your About section fresh â€” update the title, subtitle, description, and images anytime.</p>
  </div>

  <div class="body">

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.about.update', $about->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="section">
        <label>About Images</label>
        <div class="icons-row">

          <div class="icon-col">
            <div class="icon-box">
              <img src="{{ $about->image1 ? asset('images/' . $about->image1) : asset('images/placeholder.png') }}" alt="Image 1">
            </div>
            <label class="file-label" for="image1">Choose File</label>
            <input type="file" id="image1" name="image1" accept="image/*">
            <span class="file-name">No file chosen</span>
            @error('image1') <div class="error-msg">{{ $message }}</div> @enderror
          </div>

          <div class="icon-col">
            <div class="icon-box">
              <img src="{{ $about->image2 ? asset('images/' . $about->image2) : asset('images/placeholder.png') }}" alt="Image 2">
            </div>
            <label class="file-label" for="image2">Choose File</label>
            <input type="file" id="image2" name="image2" accept="image/*">
            <span class="file-name">No file chosen</span>
            @error('image2') <div class="error-msg">{{ $message }}</div> @enderror
          </div>

          <div class="icon-col">
            <div class="icon-box">
              <img src="{{ $about->image3 ? asset('images/' . $about->image3) : asset('images/placeholder.png') }}" alt="Image 3">
            </div>
            <label class="file-label" for="image3">Choose File</label>
            <input type="file" id="image3" name="image3" accept="image/*">
            <span class="file-name">No file chosen</span>
            @error('image3') <div class="error-msg">{{ $message }}</div> @enderror
          </div>

        </div>
        <p class="hint">Leave any slot empty to keep the existing image there.</p>
      </div>

      <div class="section">
        <label>Title <span class="required">*</span></label>
        <input type="text" name="title" value="{{ old('title', $about->title) }}">
        @error('title') <div class="error-msg">{{ $message }}</div> @enderror
      </div>

      <div class="section">
        <label>Subtitle</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $about->subtitle) }}">
      </div>

      <div class="section">
        <label>Sub Description</label>
        <textarea name="subdescription">{{ old('subdescription', $about->subdescription) }}</textarea>
      </div>

      <div class="section" style="margin-bottom:0;">
        <label>Description</label>
        <textarea name="description">{{ old('description', $about->description) }}</textarea>
      </div>

      <div class="footer">
        <a href="{{ route('admin.about.index') }}" class="back-link"><-- Back</a>
        <button type="submit" class="save-btn">Save Changes</button>
      </div>

    </form>

  </div>
</div>

<script>
  document.querySelectorAll('.icon-col input[type="file"]').forEach(function(input){
    input.addEventListener('change', function(e){
      var file = e.target.files && e.target.files[0];
      if(!file) return;

      var col = input.closest('.icon-col');
      var img = col.querySelector('.icon-box img');
      var nameLabel = col.querySelector('.file-name');

      var reader = new FileReader();
      reader.onload = function(evt){
        img.src = evt.target.result;
      };
      reader.readAsDataURL(file);

      nameLabel.textContent = file.name;
    });
  });
</script>

@endsection