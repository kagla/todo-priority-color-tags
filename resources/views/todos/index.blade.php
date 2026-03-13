<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>나의 할 일 목록</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        priority: {
                            high: '#EF4444',
                            medium: '#F59E0B',
                            low: '#10B981',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen py-10">

    <div class="max-w-[600px] mx-auto px-4">

        {{-- 페이지 제목 --}}
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">나의 할 일 목록</h1>

        {{-- 할 일 추가 카드 --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">

            <form action="/todos" method="POST">
                @csrf

                {{-- 텍스트 입력 --}}
                <input
                    type="text"
                    name="title"
                    placeholder="새로운 할 일을 입력하세요"
                    value="{{ old('title') }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent mb-4"
                >

                {{-- 우선순위 라디오 버튼 --}}
                <div class="flex items-center gap-6 mb-4">
                    <span class="text-sm font-medium text-gray-600">우선순위:</span>

                    {{-- 빨강(긴급) --}}
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="priority" value="high" {{ old('priority') === 'high' ? 'checked' : '' }} class="hidden peer">
                        <span class="w-4 h-4 rounded-full bg-priority-high ring-2 ring-transparent peer-checked:ring-priority-high ring-offset-2 transition-all"></span>
                        <span class="text-sm text-gray-700 peer-checked:font-semibold">긴급</span>
                    </label>

                    {{-- 노랑(보통) --}}
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="priority" value="medium" {{ old('priority', 'medium') === 'medium' ? 'checked' : '' }} class="hidden peer">
                        <span class="w-4 h-4 rounded-full bg-priority-medium ring-2 ring-transparent peer-checked:ring-priority-medium ring-offset-2 transition-all"></span>
                        <span class="text-sm text-gray-700 peer-checked:font-semibold">보통</span>
                    </label>

                    {{-- 초록(여유) --}}
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="priority" value="low" {{ old('priority') === 'low' ? 'checked' : '' }} class="hidden peer">
                        <span class="w-4 h-4 rounded-full bg-priority-low ring-2 ring-transparent peer-checked:ring-priority-low ring-offset-2 transition-all"></span>
                        <span class="text-sm text-gray-700 peer-checked:font-semibold">여유</span>
                    </label>
                </div>

                {{-- 추가 버튼 --}}
                <button
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg px-4 py-3 transition-colors"
                >
                    추가
                </button>
            </form>

            {{-- 유효성 검사 에러 표시 --}}
            @if ($errors->any())
                <div class="mt-4 text-sm text-red-500">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 할 일 목록 카드 --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-700">목록</h2>
                <span class="text-sm text-gray-400">총 {{ $todos->count() }}개</span>
            </div>

            @forelse ($todos as $todo)
                @php
                    $priorityColor = match($todo->priority->value) {
                        'high'   => 'bg-priority-high',
                        'medium' => 'bg-priority-medium',
                        'low'    => 'bg-priority-low',
                    };
                    $priorityLabel = match($todo->priority->value) {
                        'high'   => '긴급',
                        'medium' => '보통',
                        'low'    => '여유',
                    };
                @endphp

                <div class="flex items-center gap-3 p-3 rounded-xl mb-2 last:mb-0 {{ $todo->is_completed ? 'bg-gray-50' : 'bg-white border border-gray-100' }} transition-all hover:shadow-sm">

                    {{-- 우선순위 색상 배지 --}}
                    <span class="shrink-0 w-2 h-10 rounded-full {{ $priorityColor }}"></span>

                    {{-- 완료 체크박스 --}}
                    <form action="/todos/{{ $todo->id }}/toggle" method="POST" class="shrink-0">
                        @csrf
                        @method('PATCH')
                        <input
                            type="checkbox"
                            {{ $todo->is_completed ? 'checked' : '' }}
                            onchange="this.closest('form').submit()"
                            class="w-5 h-5 rounded border-gray-300 text-blue-500 focus:ring-blue-400 cursor-pointer"
                        >
                    </form>

                    {{-- 할 일 내용 --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm truncate {{ $todo->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                            {{ $todo->title }}
                        </p>
                        <span class="text-xs {{ $todo->is_completed ? 'text-gray-300' : 'text-gray-400' }}">
                            {{ $priorityLabel }} · {{ $todo->created_at->format('m/d H:i') }}
                        </span>
                    </div>

                    {{-- 삭제 버튼 --}}
                    <form action="/todos/{{ $todo->id }}" method="POST" class="shrink-0"
                          onsubmit="return confirm('정말 삭제하시겠습니까?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-400">아직 할 일이 없습니다</p>
                    <p class="text-gray-300 text-sm mt-1">위에서 새로운 할 일을 추가해보세요!</p>
                </div>
            @endforelse
        </div>

    </div>

</body>
</html>
