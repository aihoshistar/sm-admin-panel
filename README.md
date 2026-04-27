> "본 프로젝트의 상세한 데이터 수집 전략 및 테이블 설계 근거는 [DATA-STRATEGY.md](https://github.com/aihoshistar/sm-ai-backend/blob/main/docs/DATA-STRATEGY.md) 에서 확인하실 수 있습니다."

# SM Artist Global Monitoring & AI Insight Service

 - SM 엔터테인먼트 아티스트의 글로벌 팬덤 데이터를 수집하고, Gemini AI를 통해 인사이트를 도출하는 백엔드 시스템
 - 각. 서버는 최소한의 비용만 지출될 수 있도록 고려하여 설계함

## 1. 전체 시스템 구조
 - Data Layer (Oracle Cloud): Airflow를 통한 데이터 수집 자동화 및 PostgreSQL 적재.
 - Service Layer (Docker/Python): FastAPI 기반의 비즈니스 로직 처리 및 Gemini AI 연동.
 - Monitoring Layer (PHP Hosting): Laravel 기반의 수집 현황 및 AI 분석 결과 대시보드.
 - Ops Layer: Discord Webhook을 통한 실시간 장애 및 성능 지연 알림.

### TODO: 다이어그램 추가 예정

## 2. 관련 레포지토리

| Repository | Description | Key Tech |
| :--- | :--- | :--- |
| [**sm-data-pipeline**](https://github.com/aihoshistar/sm-data-pipeline) | 아티스트/펜덤 활동 데이터 크롤링 및 워크플로우 관리 | Python, Airflow, Selenium |
| [**sm-ai-backend**](https://github.com/aihoshistar/sm-ai-backend) | **[Main]** Core AI 로직 연동 및 API 서비스 제공 | FastAPI, Gemini API, Redis |
| [**sm-admin-panel**](https://github.com/aihoshistar/sm-admin-panel) | 데이터 수집 현황 시각화 및 백오피스 도구 | Laravel 13, PHP 8.x |

## 3. 주요 기능과 의사결정

### 실시간 에러 트래킹 및 성능 모니터링
 - **Latency Check:** API 처리 시간이 **0.7s를 초과**할 경우, 로그와 함께 **Discord 채널**로 알림 전송
 - **Error Handling:** 500 에러 및 크롤러 중단 발생 시 Traceback을 포함한 알림을 전송하여 대응할 수 있도록함

### 비용을 생각하여 하이브리드 인프라 전략
  * **Strategy:** Oracle Cloud(무료 인스턴스)와 개인 PHP 호스팅을 조합하여 인프라 비용 0원 달성.
  * **Portability:** 모든 환경을 **Docker 컨테이너화**하여, 추후 트래픽 및 리소스 이슈가 발생할 경우 **AWS(ECS/EKS) 또는 GCP** 등 클라우드 서비스로 마이그레이션이 가능하도록 설계.

### Gemini API 최적화와 데이터 파이프라인

  * **API Management:** Gemini API의 무료 쿼터 제한을 고려하여 Redis 캐싱 레이어를 도입, 동일 요청에 대한 중복 호출 방지.
  * **Automation:** Airflow를 활용해 수집 주기 및 실패 시 Retry 전략을 자동화하여 데이터 결손 최소화.

## 4. 기술스택

### **Backend & AI**

  * Python 3.10+, FastAPI, Gemini Pro API (LLM)
  * SQLAlchemy (ORM), PostgreSQL, Redis

### **Data Engineering**

  * Apache Airflow (Workflow Management)
  * BeautifulSoup4, Selenium (Crawling)

### **DevOps & Admin**

  * Docker
  * Laravel 13 (Admin Dashboard)
  * Discord Webhook (Monitoring)

## 4. 트래블슈팅

 * Gemini 한도..
