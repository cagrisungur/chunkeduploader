# Chunk Video Uploader

Chunk video uploader is a Php project for dealing chunked videos.

## Requirements
* [ffmpeg](https://ffmpeg.org/ffmpeg.html) is a command line toolbox to manipulate, convert and stream multimedia content.
* [RabbitMQ](https://www.rabbitmq.com/download.html) Version 2.0 or later.
* [Symfony](https://symfony.com/download) Symfony 5.xx

## Installation
```bash
$ git clone https://github.com/cagrisungur/chunkeduploader
$ cd composer install
```
#### Migrate Database
At the migration folder thanks to symfony you can create database with this command
```bash
$ php bin/console doctrine:migrations:migrate
```
# Usage

After migrate the database. Run in project dir this command
```bash
$ php bin/console server:run
```
If you have Symfony installed, you can do basically
```bash
$ symfony server:start
```

## API Collection
* You can see all endpoints with [Chunk Video Upload Handler](https://documenter.getpostman.com/view/9431903/TVK5cgWo)
* You can import the Chunk Video Upload Handler.postman_collection.json file to postman for access all collections

# Examples

#### PUT Request

```bash
curl --location --request PUT 'http://localhost:8000/api/videos/1' \
--header 'Content-Type: application/json' \
--data-raw '{
    "description":"My new desc",
    "title": "My new title"
}'
```
Response

```bash
{
  "@context": "/api/contexts/Video",
  "@id": "/api/videos/1",
  "@type": "Video",
  "id": 1,
  "thumbnail": "dd12-dlx1-x.jpg",
  "status": 1,
  "videoSecond": 90,
  "videoHash": "dd12-dlx1-x",
  "videoChunks": [],
  "name": "videoplayback1.mp4",
  "description": "My new desc",
  "lastChunk": null,
  "path": "dd12-dlx1-x.mp4",
  "title": "My new title",
  "createdAt": "2020-09-11T17:54:57+02:00",
  "updatedAt": "2020-09-11T17:54:57+02:00",
  "remainingChunk": 3
}
```

#### POST Request
```bash
curl --location --request POST 'http://localhost:8000/api/video_chunks' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--form 'file=@/C:/xampp74/htdocs/pixel90chunk/public/chunks/videoplayback1.mp4' \
--form 'uniqHash=dd12-dlx1-xyzg1' \
--form 'chunkIndex=3' \
--form 'totalChunk=3' \
--form 'description=My video description' \
--form 'title=My vide title'
```
Response:
```bash
{
  "@context": "/api/contexts/Video",
  "@id": "/api/videos/4",
  "@type": "Video",
  "id": 4,
  "thumbnail": null,
  "status": 0,
  "videoSecond": 0,
  "videoHash": "dd12-dlx1-xyzg1",
  "videoChunks": [
    "/api/video_chunks/10",
    "/api/video_chunks/11",
    "/api/video_chunks/12"
  ],
  "name": "videoplayback1.mp4",
  "description": null,
  "lastChunk": null,
  "path": null,
  "title": null,
  "createdAt": "2020-09-11T18:12:23+02:00",
  "updatedAt": "2020-09-11T18:12:23+02:00",
  "remainingChunk": 0
}
```