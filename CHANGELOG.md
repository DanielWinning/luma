# Luma | Luma Framework Changelog

## [2.0.0] - 2024-11-20
### Added
- Added Tailwind
- Added PHPUnit

---

## [1.3.0] - 2024-08-24
### Added
- Added `ENVIRONMENT` and admin credential variables to `.env.example`
- Added new `luma:security:populate` command to populate security tables

### Changed
- Update TypeScript build target to ES6
- Update setup SQL script to remove `INT` column lengths

### Deprecated
- N/A

### Removed
- Removed `data/populate.php` in favour of the new `luma:security:populate` command
- Removed username from the default `User` model

### Fixed
- N/A

### Security
- N/A

---

## [1.2.0] - 2024-07-16
### Added
- Add `refresh` override to `Security\Entity\User` to ensure `Role` is always joined

### Changed
- Changed default security button hover text colour to work with the default as well as light backgrounds.

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Fix for initial database setup where `strUsername` was not using a `UNIQUE KEY`
- Fix for initial database setup where `strPassword` was using a `UNIQUE KEY`

### Security
- N/A

---

## [1.1.0] - 2024-05-06
### Added
- Added caching for config files
- Added commands using `bin/luma`
- Added base `luma:cache:clear` command

### Changed
- Using route protection attributes in `SecurityController`.

### Deprecated
- N/A

### Removed
- Removed `AuthenticationHelper` in favour of attributes.

### Fixed
- Fix for repeat password field displaying 'Password'.

### Security
- N/A

---

## [1.0.0] - 2024-04-29
### Added
- Added `CHANGELOG.md`
- Implemented `lumax/security-component`
- See https://github.com/DanielWinning/framework-component/releases/tag/1.0.0

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Updated dependencies

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