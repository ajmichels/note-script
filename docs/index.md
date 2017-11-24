---
title: Note-Script
layout: default
---

![build status](https://travis-ci.org/ajmichels/note-script.svg?branch=master)

# Synopsis

`note [-t=<title>][-v|-vv|-vvv]`

# Description

A tool for quickly creating plain text notes in a markdown format. The command will
return the path to the file that was created in STDOUT.

Example Filenames:
* `2017-11-23-20-35-21.md`
* `2017-11-23-20-35-21_my-title.md`

# Options

* `--title=<title>, -t=<title>`<br>
  An optional title to give the note.
* `-v, -vv, -vvv`<br>
  Verbose output.

# Configuration

By default the command will attempt to place notes in a 'notes' directory inside the
directory specified by the "HOME" environment variable. If there is no HOME
environment variable present an exception will be thrown. This behavior can be
modified by defining a "NOTE_HOME" environment variable with a with a directory
path for its value.

# Examples

Create a note and then open it for editing.<br>
`vim $(note)`

Pipe standard input (STDIN) to the command and a note will be created with that
content.<br>
`echo 'My note content.' | note`

Add a title to the note.<br>
`vim -t "My Title"`

# Background

I created this tool to help me take notes faster. I knew I wanted to take notes
as plain text files written with Markdown. I didn't want to lock my notes into
a proprietary format such as OneNote or Evernote. I had tried that before and
while those tools are feature rich I don't like the idea of being locked into
those ecosystems. Using plain text files and Markdown allows me to use a
large number of editing tools and to store my notes where ever and however I
want.

# Authors and Contributors

Currently the only contributor is me, AJ Michels
([@ajmichels](https://github.com/ajmichels)). I have been working in software
development for over a decade now in a variety of industries.

# Support or Contact

If you are having trouble using this tool or would like to request a feature
please submit an issue to the projects [Issue
Tracker](https://github.com/ajmichels/note-script/issues).
