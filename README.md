# IoT 센서 대시보드 시스템

웹훅(Webhook)을 통해 센서 데이터를 수집하고, 반응형 웹 대시보드에서 실시간으로 시각화하는 환경 모니터링 시스템입니다.

## 🚀 주요 기능
- **실시간 대시보드**: 온도, 습도, 미세먼지, CO2 등 다양한 센서 데이터를 실시간으로 모니터링
- **멀티 디바이스 지원**: 연결된 모든 장비를 자동으로 감지하고 목록으로 표시 (드롭다운 선택 가능)
- **웹훅(Webhook) 연동**: 간편한 URL 방식의 데이터 입력 (`/webhook/{장비이름}`)
- **REST API 제공**: 저장된 데이터를 JSON으로 쉽게 조회 (`/get_data/{장비이름}`)
- **보안 설정**: DB 접속 정보 분리 및 보안 적용 (`config.php`, `.htaccess`)

---

## 🛠 설치 방법

### 1. 파일 업로드
PHP 호스팅 서버의 `public_html` (또는 `www`) 폴더에 아래 파일들을 업로드하세요:
- `index.php` (메인 대시보드)
- `get_data.php` (API 백엔드)
- `webhook.php` (데이터 수신부)
- `db_connect.php` (DB 연결 모듈)
- `config.php` (설정 파일)
- `htaccess.txt` (서버 설정 파일 - **업로드 후 `.htaccess`로 이름 변경 필수**)

### 2. 데이터베이스 설정
1. 호스팅 관리자 페이지에서 MySQL/MariaDB 데이터베이스를 생성합니다 (예: `iot_data`).
2. 제공된 `schema.sql` 파일을 실행(가져오기)하여 테이블을 생성합니다.
   - *참고: 이미 테이블이 있다면 `update_schema.sql`을 실행하여 `device_uuid` 컬럼을 추가하세요.*

### 3. 연결 설정 (`config.php`)
`config.php` 파일을 열어 호스팅 DB 정보를 입력하세요:
```php
return [
    'DB_HOST' => 'localhost', // 보통 localhost지만, 호스팅에 따라 다를 수 있음
    'DB_NAME' => '아이디_iot_data',
    'DB_USER' => '호스팅_아이디',
    'DB_PASS' => '호스팅_비밀번호',
];
```

---

## 📡 사용 가이드

### 1. 데이터 보내기 (Webhook)
센서 장비나 n8n 등에서 **POST** 방식으로 아래 주소에 데이터를 쏘면 됩니다. URL 뒤의 `{장비이름}`은 마음대로 정할 수 있습니다 (예: `living-room`, `factory-01`).

- **주소**: `https://내도메인.com/webhook/{장비이름}`
- **방식**: `POST`
- **본문(Body)**: JSON 형식
```json
{
  "Temperature": 24.5,
  "Humidity": 60,
  "CO2": 450,
  "PM2.5": 10
  ... (기타 필드)
}
```

### 2. 데이터 보기 (대시보드)
브라우저에서 내 도메인으로 접속하면 됩니다.
- **주소**: `https://내도메인.com`
- 상단의 드롭다운 메뉴에서 원하는 장비를 선택하면 해당 장비의 데이터가 표시됩니다.

### 3. API 데이터 조회 (Raw Data)
다른 시스템에서 데이터를 가져가고 싶을 때 사용합니다.
- **전체 장비 최신값**: `https://내도메인.com/get_data`
- **특정 장비 최신값**: `https://내도메인.com/get_data/{장비이름}`

---

## 🤖 n8n 자동화 연동 가이드
n8n을 사용하여 주기적으로 데이터를 보내려면 **HTTP Request** 노드를 사용하세요.

1. **HTTP Request** 노드 생성
2. **Method**: `POST`
3. **URL**: `https://내도메인.com/webhook/test-device`
4. **Authentication**: None (없음)
5. **Body Content Type**: `JSON`
6. **JSON 예시**:
```json
{
  "SHT30_Humidity": 45.5,
  "PM10": 12,
  "PM2.5": 5,
  "Temperature": 23.5,
  "Sound_dB": 55.2,
  "ip": "192.168.0.100",
  "SHT30_Temperature": 23.1,
  "VOC": 15,
  "Humidity": 46.0,
  "PM1.0": 2,
  "Sound_Vpp": 0.02,
  "CO2": 450
}
```
