# Magento 2 PDF Lookbook Module

A Magento 2 module that allows store administrators to upload and manage PDF catalogs/lookbooks and display them to customers in an elegant grid layout with two viewing options: embedded PDF or interactive flipbook.

## Features

- **Admin Management**: Easy-to-use admin interface for managing PDF lookbooks
- **Dual Viewing Modes**: 
  - Embedded PDF viewer using browser's native PDF capabilities
  - Interactive Flipbook viewer using Turn.js for a book-like experience
- **Grid Display**: Responsive grid layout for displaying lookbooks on the Catalogs page
- **Slider Widget**: CMS widget for displaying lookbooks in a carousel slider format
- **Store View Support**: Assign lookbooks to specific store views
- **Sorting Options**: Control the display order of lookbooks
- **Responsive Design**: Works on all devices (desktop, tablet, mobile)

## Installation

### Using Composer (recommended)

```bash
composer require vendor/pdf-lookbook
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual Installation

1. Download the extension
2. Upload the files to `app/code/Pdf/Lookbook/`
3. Run the following commands:

```bash
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

## Configuration

1. Go to **Stores > Configuration > PDF Lookbook > General Settings**
2. Enable the module
3. Configure the number of lookbooks to display per page
4. Choose the PDF display mode:
   - **Embedded in Page**: Display PDFs using the browser's native PDF viewer
   - **Flipbook**: Display PDFs as an interactive flipbook using Turn.js

## Usage

### Admin Panel

1. Go to **Content > Lookbook PDFs > Manage Lookbooks**
2. Click **Add New Lookbook**
3. Fill in the required information:
   - Title
   - Description (optional)
   - PDF File (required)
   - Cover Image (required)
   - Status (enabled/disabled)
   - Sort Order
   - Store View

### Frontend

The lookbooks will be displayed at `/lookbook` on your store. Customers can browse the lookbooks and view the PDFs by clicking on them.

### CMS Widget

You can add a lookbook slider to any CMS page or block:

1. Edit a CMS page or block
2. Click **Insert Widget**
3. Select **PDF Lookbook Slider** as the widget type
4. Configure the widget:
   - Slider Title
   - Number of Lookbooks to display
5. Save the page or block

## Technical Details

- The module uses Turn.js library from CDN to ensure the latest version is always used
- PDF.js is used to render PDF pages in the flipbook mode
- The module creates the following database table:
  - `pdf_lookbook`: Stores lookbook information including title, description, file paths, etc.

## Requirements

- Magento 2.3.x, 2.4.x
- PHP 7.3 or higher
- Modern browser with JavaScript enabled

## Support

If you encounter any issues or have questions, please contact me

## License

[MIT License](LICENSE)
