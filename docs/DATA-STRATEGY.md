# 데이터 수집 전략

SM 엔터테인먼트 아티스트 글로벌 경쟁력 강화 및 팬덤 니즈 파악을 위해 3대 핵심 영역(글로벌 지표, 실시간 트렌드, 국내외 여론) 데이터를 수집 후 분석

![System ERD](https://github.com/aihoshistar/sm-ai-backend/blob/main/docs/ERD.png?raw=true)

## 1. 데이터 수집 목적

  * **Artist Growth Tracking:** 유튜브 및 스포티파이 지표를 통한 글로벌 성장세 모니터링.
  * **Fan Sentiment Analysis:** 커뮤니티 및 SNS 여론 분석을 통한 리스크 관리 및 마케팅 전략 수립.
  * **AI-Powered Insights:** 방대한 비정형 데이터를 Gemini AI로 요약하여 의사결정 속도 개선.

## 2. SNS 플랫폼별 수집 항목 및 활용 방안

### [A] YouTube (Global Engagement)

  * **테이블:** `sns_youtube_stats`
  * **수집 데이터:** 조회수, 좋아요 수, 댓글 수, 상위 댓글(Top Comments) 텍스트
  * **활용:** 신규 뮤직비디오 공개 직후 초기 반응 수치화
      * 외국어 댓글 비중 분석을 통한 국가별 팬덤 분포 추정 (AI 분석 연동)

### [B] X (Real-time Trend)

  * **테이블:** `sns_x_trends`
  * **수집 데이터:** 아티스트 관련 해시태그 언급량, 실시간 트렌드 키워드, 샘플 트윗
  * **활용:** 컴백, 콘서트 등 특정 이벤트 시점의 화제성 폭발력 측정
      * 부정적 이슈 발생 시 확산 속도 모니터링

### [C] Communities (Domestic Sentiment)

  * **테이블:** `sns_community_posts`
  * **수집 데이터:** 더쿠(Theqoo), 인스티즈(Instiz) 등 게시글 제목 및 본문, 조회수
  * **활용:** 국내 핵심 팬덤의 여론 동향 파악
      * 아티스트 관련 밈(Meme)이나 긍정적 콘텐츠 소스 발굴

## 3. 데이터 파이프라인 설계 원칙 (Engineering Principles)

### 3.1. 확장성을 고려한 SNS Centric 설계

  * 각 플랫폼마다 데이터 구조(Schema)가 상이하므로, 하나의 테이블에 억지로 맞추지 않고 **플랫폼별 전용 테이블** 을 구성
  * 이는 특정 플랫폼의 API 정책 변경이나 크롤러 수정 시 다른 파이프라인에 영향을 주지 않는 **격리성(Isolation)** 을 보장

### 3.2. AI 분석 효율화 (Data Pre-processing)

  * 모든 텍스트를 저장하는 대신, AI 분석에 유의미한 **'상위 댓글'** 이나 **'샘플 텍스트'** 위주로 1차 정제하여 저장
  * 이는 Gemini API 호출 시 **Token 소모량을 최적화**하고 비용을 절감하기 위한 설계

### 3.3. 성능 및 장애 대응

  * **Indexing:** 대용량 데이터 조회 성능을 위해 `artist_id`와 `collected_at` 복합 인덱스 적용
  * **Monitoring:** 수집 과정에서 발생하는 모든 지연 시간을 `system_performance_logs`에 기록하여 0.7초 이상의 병목 구간을 즉시 파악

## 4. 데이터 흐름도 (Data Flow)

1.  **Extraction:** Airflow가 각 SNS 사이트에서 정해진 주기마다 크롤링 수행
2.  **Loading:** 정제된 데이터를 MySQL의 각 SNS 전용 테이블에 직접 SQL로 적재
3.  **Transformation (AI):** Backend 서버가 미분석 데이터를 추출하여 Gemini API로 분석 후 `ai_analysis_results`에 저장
4.  **Visualization:** Laravel 대시보드를 통해 최종 인사이트를 마케팅 담당자에게 시각화하여 제공
