# SM Admin Panel

PHP (Laravel) 기반 관리자 대시보드. SNS 데이터 시각화 및 AI 분석 결과 조회.

## Tech Stack

- Laravel 13
- MySQL (분석 결과 조회)
- Blade Templates
- Chart.js (데이터 시각화)

## Quick Start

### Docker로 실행 (권장)

```bash
# 루트 디렉토리에서
cd sm-artist-insights
./scripts/start.sh
```

대시보드: http://localhost:8080

### 로컬 개발

```bash
# 의존성 설치
composer install
npm install

# 환경변수 설정
cp .env.example .env
php artisan key:generate

# 마이그레이션
php artisan migrate

# 서버 실행
php artisan serve --port=8080
npm run dev
```

## 디렉토리 구조

```
sm-admin-panel/
├── app/
│   ├── Http/Controllers/   # 컨트롤러
│   ├── Models/             # Eloquent 모델
│   └── Providers/
├── database/
│   ├── migrations/         # DB 스키마
│   └── seeders/            # 샘플 데이터
├── resources/
│   ├── views/              # Blade 템플릿
│   ├── css/                # 스타일
│   └── js/                 # 프론트엔드
└── routes/
    └── web.php             # 라우팅
```

## 주요 기능

- **아티스트 대시보드**: SNS 통계 실시간 조회
- **트렌드 차트**: YouTube, X, 커뮤니티 데이터 시각화
- **AI 분석 결과**: 감성 분석 및 트렌드 예측
- **데이터 테이블**: 크롤링 데이터 목록

## 환경변수

```bash
# MySQL
DB_HOST=mysql
DB_DATABASE=sm_artist_insights
DB_USERNAME=root
DB_PASSWORD=your_password

# Laravel
APP_NAME="SM Artist Insights"
APP_URL=http://localhost:8080
```

## 마이그레이션

```bash
# 테이블 생성
php artisan migrate

# 샘플 데이터 (선택)
php artisan db:seed
```

## 상세 문서

전체 프로젝트 구조 및 설정: [루트 README](../README.md)  
데이터 전략: [docs/DATA-STRATEGY.md](./docs/DATA-STRATEGY.md)
