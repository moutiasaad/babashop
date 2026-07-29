<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>{{ config('app.name') }} - حذف الحساب</title>
  <link rel="stylesheet" href="/admin/css/bootstrap.rtl.min.css">
</head>
<body>
  <div class="container py-5">
    <div class="col-lg-6 mx-auto">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-3">حذف الحساب</h4>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('client.account.delete') }}">
            @csrf
            @method('DELETE')

            <div class="mb-3">
              <label class="form-label">أدخل كلمة المرور لتأكيد الحذف</label>
              <input type="password" name="password" class="form-control" placeholder="••••••••">
            </div>

            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" id="confirm" name="confirm" value="1">
              <label class="form-check-label" for="confirm">
                أؤكد أنني أرغب في حذف حسابي نهائيًا.
              </label>
            </div>

            <div class="d-flex gap-2">
              <a href="{{ url()->previous() }}" class="btn btn-light">تراجع</a>
              <button type="submit" class="btn btn-danger"
                      onclick="return confirm('هل أنت متأكد من حذف حسابك نهائيًا؟')">
                حذف الحساب
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
  <script src="/admin/js/bootstrap.bundle.min.js"></script>
</body>
</html>
