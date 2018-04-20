
## 배포

 * Wordpress Dable Plugin SVN Repository: https://plugins.svn.wordpress.org/dable/
 * SVN Repository를 체크아웃하여 코드 수정 후 커밋한다.
 * 코드 수정 시 버전업 필수.
 * 계정: https://trello.com/c/nj4PnJ3c#comment-59424629eae177ae26d3b709 참고

## 마이그레이션

* 데이터베이스의 변경이 있을 때는 반드시 메이저 버전을 판올림해야 합니다.
* 메이저 버전 변경시에는 `migrate.php` 파일에 `dable_migrate_from_1_to_2()`와 같이 함수를 마이그레이션 함수를 작성해두면 자동으로 실행됩니다. `from` 뒤에는 예전 버전을, `to` 뒤에 새 버전을 입력합니다. 버전 1에서 3과 같이 두 단계를 건너뛰는 경우에는 자동으로 `1_to_2`, `2_to_3` 함수가 순차적으로 실행됩니다.
* 마이그레이션 함수에서 `false`를 반환하면 즉시 마이그레이션을 중단하고 에러를 기록합니다.
