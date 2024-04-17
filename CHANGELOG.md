# Luma | Luma Framework Changelog

## [Unreleased]
### Added
- Added `CHANGELOG.md`
- Implemented `lumax/security-component`

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Updated `lumax/aurora-db` from `1.0.0` to `2.4.0`

---

## [0.3.0] - 2024-03-17
### Added
- Added database component (`lumax/aurora-db`).

### Changed
- `LumaController::respond` method now accepts 3 arguments; `$data`, `$contentType` and `$statusCode`.
- Updated the `LumaController::render` method to make the `$data` argument optional, now defaulting to an empty array.

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Fix for incorrect headers being set when calling controller response methods.
- Fix for controller responses always returning `200` status code.
- Fix for debugging bar not displaying when `Content-Length` header is set.

### Security
- N/A