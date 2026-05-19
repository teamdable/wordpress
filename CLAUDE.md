# Dable for WordPress Plugin

WordPress 블로그 플러그인. 매체 페이지에 Dable 위젯(추천/광고)을 삽입한다.

## 로컬 테스트 셋업

1. [Local by Flywheel](https://localwp.com/) 설치 및 사이트 생성
2. 플러그인 디렉토리에 symlink 생성:
    ```bash
    ln -s ~/wordpress ~/Local\ Sites/testsite/app/public/wp-content/plugins/dable
    ```
3. Local 사이트 시작 후 WP Admin > Plugins에서 Dable 플러그인 활성화
4. 코드 수정 시 `~/wordpress/`에서 작업하면 symlink를 통해 즉시 반영됨

## 참고

- [Notion 배포 가이드](https://www.notion.so/dableglobal/Wordpress-77ad6938a13541489827a087da5af108)
