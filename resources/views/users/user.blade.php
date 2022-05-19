    <div class="card mt-3">
      <div class="card-body">
        <div class="d-flex flex-row">
          <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
            <i class="fas fa-user-circle fa-3x"></i>
          </a>
          {{-- ログインユーザーがユーザーページのユーザーと異なる場合のみフォロー可能 --}}
          @if( Auth::id() !== $user->id )
            <follow-button
              class="ml-auto"
              {{-- boolean値もVueに渡す時はJson形式 --}}
              :initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'

              {{-- ログインユーザーの有無 --}}
              :authorized='@json(Auth::check())'

              {{-- 'user/follow'へのルート情報とこのユーザーページのusernameを渡す --}}
              endpoint="{{ route('users.follow', ['name' => $user->name]) }}"
            >
            </follow-button>
          @endif
        </div>
        <h2 class="h5 card-title m-0">
          <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
            {{ $user->name }}
          </a>
        </h2>
      </div>
      <div class="card-body">
        <div class="card-text">
          <a href="{{ route('users.followings', ['name' => $user->name]) }}" class="text-muted">
            {{ $user->count_followings }}フォロー 
          </a>
          <span class="m-1"></span>
          <a href="{{ route('users.followers', ['name' => $user->name]) }}" class="text-muted">
            {{ $user->count_followers }}フォロワー
          </a>
        </div>
      </div>
    </div>