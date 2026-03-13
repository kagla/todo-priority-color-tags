# Todo Priority Color Tags

우선순위별 색상 태그가 있는 할 일 목록 애플리케이션 (Laravel 12.x)

## 요구 사항

- PHP 8.2 이상
- Composer
- MySQL 5.7 이상

## 설치

```bash
# 1. 저장소 클론
git clone <repository-url>
cd todo-priority-color-tags

# 2. 의존성 설치
composer install

# 3. 환경 설정 파일 복사 및 앱 키 생성
cp .env.example .env
php artisan key:generate

# 4. .env 파일에서 데이터베이스 설정
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=todo_priority_color_tags
# DB_USERNAME=root
# DB_PASSWORD=

# 5. MySQL에서 데이터베이스 생성
mysql -u root -p -e "CREATE DATABASE todo_priority_color_tags;"

# 6. 마이그레이션 실행
php artisan migrate

# 7. 개발 서버 실행
php artisan serve
```

브라우저에서 `http://localhost:8000` 접속

## 사용 방법

### 할 일 추가
1. 입력 필드에 할 일 제목을 입력
2. 우선순위 선택 (긴급/보통/여유)
3. "추가" 버튼 클릭

### 우선순위 색상
| 우선순위 | 색상 | 값 |
|---------|------|-----|
| 긴급 | 빨강 (#EF4444) | high |
| 보통 | 노랑 (#F59E0B) | medium |
| 여유 | 초록 (#10B981) | low |

### 완료 처리
- 체크박스를 클릭하면 완료/미완료 상태가 토글됩니다
- 완료된 항목은 회색 + 취소선으로 표시됩니다

### 삭제
- 휴지통 아이콘 클릭 → 확인 후 삭제

## 프로젝트 구조

```
app/
├── Enums/Priority.php          # 우선순위 Enum (high, medium, low)
├── Models/Todo.php             # Todo 모델
└── Http/Controllers/
    └── TodoController.php      # CRUD 컨트롤러

database/migrations/
└── xxxx_create_todos_table.php # todos 테이블 마이그레이션

resources/views/todos/
└── index.blade.php             # 메인 뷰 (Tailwind CSS CDN)

routes/
└── web.php                     # 라우트 정의
```

## API 라우트

| 메서드 | URI | 동작 |
|--------|-----|------|
| GET | `/` | 할 일 목록 표시 |
| POST | `/todos` | 할 일 추가 |
| PATCH | `/todos/{id}/toggle` | 완료 상태 토글 |
| DELETE | `/todos/{id}` | 할 일 삭제 |

## 라이선스

MIT
