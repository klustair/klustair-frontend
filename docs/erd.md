
DB ER Digagram

```mermaid
erDiagram
    REPORT ||..|| REPORTS_SUMMARY : has
    REPORT ||..|{ NAMESPACE : has
    NAMESPACE }|..|{ AUDIT : has
    CONTAINER }|..|{ AUDIT : has
    NAMESPACE ||..o{ POD : has
    POD ||..o{ CONTAINER : has
    CONTAINER }o..|| IMAGE : has
    IMAGE }o..o{ VULN : has
    VULN }|..|| VULN_DETAIL : has
    VULN ||..o{ VULNSUMMARY : has
    VULN ||..|| VULNWHITELIST : has
    VULN }o..o{ CWE : has
    USER {
      bigint id 
      varchar name
      varchar email
      timestamp email_verified_at
      varchar password
      varchar remember_token
      timestamp created_at
      timestamp updated_at
    }
    PASSWORD_RESET
    PERSONAL_ACCESS_TOKEN
    MIGRATION
```