<!DOCTYPE html>
<html lang="{{ with .Site.LanguageCode }}{{ . }}{{ else }}en-us{{ end }}">
  {{ partial "head.html" . }}
  
  <body>
    {{ if .IsHome }}
    {{ "<!-- header -->" | safeHTML }}
    <header class="shadow-bottom-home sticky-top bg-dark">
      {{ partial "navigation.html" . }}
    </header>
    {{ "<!-- /header -->" | safeHTML }}
    
    {{ else }}
    {{ "<!-- header -->" | safeHTML }}
    <header class="shadow-bottom sticky-top bg-white">
      {{ partial "navigation.html" . }}
    </header>
    {{ "<!-- /header -->" | safeHTML }}
    {{ end }}
    {{ block "main" . }}{{ end }}
    {{ partialCached "footer.html" . }}
  </body>

</html>