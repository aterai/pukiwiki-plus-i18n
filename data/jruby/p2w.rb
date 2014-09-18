# -*- mode: ruby; encoding: utf-8 -*-
require 'uri'

module HTMLUtils
  ESC = {
    '&' => '&amp;',
    '"' => '&quot;',
    '<' => '&lt;',
    '>' => '&gt;'
  }
  def escape(str)
    table = ESC   # optimize
    str.gsub(/[&"<>]/u) {|s| table[s]} #"
  end
#   CODE = {
#     '<' => '&lt;',
#     '>' => '&gt;',
#     '&' => '&amp;'
#   }
#   def code_escape(str)
#     table = CODE
#     str.gsub(/[<>&]/n) {|s| table[s]}
#   end
  URIENC = {
    '(' => '%28',
    ')' => '%29',
    ' ' => '%20'
  }
  def uri_encode(str)
    table = URIENC
    str.gsub(/[\(\) ]/u) {|s| table[s]}
  end
  def urldecode(str)
    str.gsub(/[A-F\d]{2}/) {|x| [x.hex].pack('C*')}
  end
end

class PukiWikiParser
  include HTMLUtils

  def initialize()
    @h_start_level = 2
  end
  def filename(pw_name)
    decoded_name = HTMLUtils.urldecode(pw_name).sub(/\:/, '_').split("/").last
    return decoded_name.sub(/\.txt$/, '.wiki')
  end
#   def timestamp()
#     @timestamp
#   end
  def to_md(src, page_names, page, base_uri = 'https://code.google.com/p/java-swing-tips/wiki/', suffix= '/')
    @page_names = page_names
    @base_uri = base_uri
    @page = page.sub!(/\.txt$/, '')
    @pagelist_suffix = suffix
    @inline_re = nil # invalidate cache

    @timestamp = ''
    @title  = ''
    @author = ''
    @tags = ''

    buf = []
    lines = src.rstrip.split(/\r?\n/).map {|line| line.chomp }
    while lines.first
      case lines.first
      when ''
        buf.push lines.shift
        while lines.first.empty? or lines.first =~ /\A- &/
          lines.shift
        end
      when /\ATITLE:/
        @title = lines.shift.sub(/\ATITLE:/, '')
      when /\ARIGHT:/
        /at (\w{4}-\w{2}-\w{2})/ =~ lines.first
        @timestamp = $1
        buf.push parse_inline(lines.shift.sub(/\ARIGHT:/, '').concat("\n"))
      when /\A----/
        lines.shift
        buf.push '----' #hr
      when /\A\*/
        buf.push parse_h(lines.shift)
      when /\A\#code.*\{\{/
        buf.concat parse_pre(take_multi_block(lines))
      when /\A\#.+/
        tmp = parse_block_plugin(lines.shift)
        buf.push tmp if tmp
      when /\A\s/
        buf.concat parse_pre(take_block(lines, /\A\s/))
#         lines.shift
      when /\A- &/
        lines.shift
        while lines.first.empty? or lines.first =~ /\A- &/
          lines.shift
        end
      when /\A\/\//
        #buf.concat parse_comment(take_block(lines, /\A\/\//))
        take_block(lines, /\A\/\//)
      when /\A>/
        buf.concat parse_quote(take_block(lines, /\A>/))
      when /\A-/
        buf.concat parse_list('ul', take_block(lines, /\A-/))
      when /\A\+/
        buf.concat parse_list('ol', take_block(lines, /\A\+/))
      when /\A:/
        buf.concat parse_dl(take_block(lines, /\A:/))
      else
        buf.concat parse_p(take_block(lines, /\A(?![*\s>:\-\+\#]|----|\z)/))
      end
    end

    unless @tags.empty? then
      head = []
      head.push("#labels #{@tags}")
      head.join("\n").strip.concat(buf.join("\n"))
    else
      buf.join("\n").lstrip
    end
  end

  private

  def take_block(lines, marker)
    buf = []
    until lines.empty?
      break unless marker =~ lines.first
      if /\A\/\// =~ lines.first then
        lines.shift
      else
        buf.push lines.shift.sub(marker, '')
      end
    end
    buf
  end

  def take_multi_block(lines)
    buf = []
    until lines.empty?
      l = lines.shift
      break if /^\}\}$/ =~ l
      next  if /^.code.*$/ =~ l
      buf.push l
    end
    buf
  end

  def parse_h(line)
    level = @h_start_level + (line.slice(/\A\*{1,4}/).length - 1)
    h = "="*level
    content = line.gsub(/\A\*+(.+)$/) { $1.gsub(/ +\[#\w+\]$/, "") }
    "#{h} #{parse_inline(content).strip} #{h}"
  end

  def parse_list(type, lines)
    marker = ((type == 'ul') ? /\A-+/ : /\A\++/)
    parse_list0(type, lines, marker)
  end

  def parse_list0(type, lines, marker)
    buf = []
    until lines.empty?
      line = lines.shift.strip
      aaa = line.slice(marker)
      if aaa then
        level = aaa.length
        line = line.sub(marker,'').strip
      else
        level = 0
      end
      s = (type == 'ul') ? '*' : '#'
      h = "  "*(level+1)
      buf.push "#{h}#{s} #{parse_inline(line)}"
    end
    buf
  end

  def parse_dl(lines)
    buf = ["<dl>"]
    lines.each do |line|
      dt, dd = *line.split('|', 2)
      buf.push "<dt>#{parse_inline(dt)}</dt>"
      buf.push "<dd>#{parse_inline(dd)}</dd>" if dd
    end
    buf.push "</dl>"
    buf
  end

  def parse_quote(lines)
    ["<blockquote><p>", lines.join("\n"), "</p></blockquote>"]
  end

#   def parse_pre(lines)
#     #[%Q|#{lines.map {|line| "\t".concat(line) }.join("\n")}|, %Q|{:class="prettyprint"}|]
#     lines.map{|line| "\t".concat(line)} #.join("\n")
#   end
#
#   def parse_pre2(lines)
#     array = lines.map{|line| code_escape(line)}
#     array[0] = %Q|<pre class="prettyprint"><code>|.concat(array[0])
#     [array.join("\n"), "</code></pre>"]
#   end

  def parse_pre(lines)
    ["{{{", lines.join("\n"), "}}}"]
  end

  def parse_comment(lines)
    ["<wiki:comment> #{lines.map {|line| escape(line) }.join("\n")}", "</wiki:comment>"]
  end

  def parse_p(lines)
    lines.map {|line| parse_inline(line)}
  end

  def parse_inline(str)
    str.gsub!(/%%(?!%)((?:(?!%%).)*)%%/) { ['~~', $1, '~~'].join() } #<del>, <strike>
    str.gsub!(/``(?!`)((?:(?!``).)*)``/) { ['`', $1, '`'].join() }   #<code>
    str.gsub!(/\'\'(?!\")((?:(?!\'\').)*)\'\'/) { ['*', $1, '*'].join() } #<strong>
    str.gsub!(/KBD{([^}]*)}/) { ['`', $1, '`'].join() } #<kbd>
#     str.gsub!('%%', '~~')  #<del>, <strike>
#     str.gsub!("\'\'", '*') #<strong>
#     str.gsub!("``", '`')   #<code>
    @inline_re ||= %r!
        &([A-Za-z]+)(?:\(([^\)]+)\))?(?:{([^}]+)})?; # $1: plugin, $2: parameter, $3: inline
      | \[\[([^>]+)>?([^\]]*)\]\]     # $4: label,  $5: URI
      | \[(https?://\S+)\s+([^\]]+)\] # $6: label,  $7: URI
      | (#{autolink_re()})            # $8: Page name autolink
      | (#{URI.regexp('http')})       # $9: URI autolink
    !x
    str.gsub(@inline_re) {
      case
      when plugin   = $1 then parse_inline_plugin(plugin.strip, $2, $3)
      when bracket  = $4 then a_href($5.strip, bracket, 'pagelink')
      when bracket  = $7 then a_href($6.strip, bracket, 'outlink')
      when pagename = $8 then a_href(page_uri(pagename), pagename, 'pagelink')
      when uri      = $9 then a_href(uri, uri, 'outlink')
      else
        raise 'must not happen'
      end
    }
  end

  def parse_inline_plugin(plugin, para, inline)
    case plugin
#     when 'jnlp'
#       %Q|<a href="http://terai.xrea.jp/#{@page.downcase}/example.jnlp" onclick="_gaq.push(['_trackEvent', 'WebStart', 'Launch', '#{@page}']);"><img style="cursor:pointer" width="88" height="23" src="http://lh4.ggpht.com/_9Z4BYR88imo/TRD2KGq73BI/AAAAAAAAAwA/N8-6EXongNk/s800/webstart.png" title="Java Web Start" alt="Launch" /></a>|
#     when 'jar'
#       %Q|<a href="http://terai.xrea.jp/#{@page.downcase}/example.jar" onclick="_gaq.push(['_trackEvent', 'Jar', 'Download', '#{@page}']);">Jar file(example.jar)</a>|
#     when 'zip'
#       %Q|<a href="http://terai.xrea.jp/#{@page.downcase}/src.zip" onclick="_gaq.push(['_trackEvent', 'Source', 'Download', '#{@page}']);">Source(src.zip)</a>\n  * <a href="http://java-swing-tips.googlecode.com/svn/trunk/#{@page.sub(/Swing\//,'')}" onclick="_gaq.push(['_trackEvent', 'Subversion', 'View', '#{@page}']);">Repository(svn repository)</a>|
    when 'author'
      @author = para.strip #.delete("()")
      if @author == "aterai" then
        uname = 'at.terai@gmail.com'
      else
        uname = @author
      end
      %Q|[https://code.google.com/u/#{uname}/ #{@author}]|
    when 'new'
      inline.strip #.delete("{}")
    else
      para
    end
  end

  def parse_block_plugin(line)
    @plugin_re = %r<
        \A\#([^\(]+)\(?([^\)]*)\)?
      >x
    args = []
    line.gsub(@plugin_re) {
      args.push $1
      args.push $2 #.slice(",")
    }
    buf = []
    case args.first
    when 'download'
      buf.push %Q|<wiki:gadget url="https://java-swing-tips.googlecode.com/svn/trunk/tooltable.xml" up_img="#{args[1]}" width="100%" border="0" />|
    when 'ref'
      buf.push %Q<#{args[1]}>
    when 'tags'
      @tags = args[1]
    else
      buf.push ''
    end
    buf
  end

  def a_href(uri, label, cssclass)
    str = label.strip
    if(cssclass.casecmp('pagelink')==0) then
      if(uri.size===0) then
        %Q<[#{str}]>
      else
        %Q<[#{@base_uri}#{uri_encode(uri.strip)} #{str}]>
      end
    else
      #%Q<[#{URI.escape(uri.strip)} #{str}]>
      %Q<[#{uri_encode(uri.strip)} #{str}]>
    end
  end

  def autolink_re
    Regexp.union(* @page_names.reject {|name| name.size <= 3 })
  end

  def page_uri(page_name)
    "#{@base_uri}#{urldecode(page_name)}#{@pagelist_suffix}"
  end
end

def main
  include HTMLUtils
  srcpath = ARGV[0]
  tgtpath = ARGV[1]

  if File.exist?(srcpath)
    Dir::glob("#{srcpath}/5377696E672F*.txt").each {|f|
    #Dir::glob("#{srcpath}/*.txt").each {|f|
      fname = File.basename(f)
      tbody = File.read(f)
      page_names = []
      parser = PukiWikiParser.new()
      buf    = parser.to_md(tbody, page_names, HTMLUtils.urldecode(fname))
      tmp = parser.filename(fname)

      unless /^[0-9_]/ =~ tmp
        nname  = [tgtpath, tmp].join('/')
        puts tmp
        outf   = open(nname, "wb")
        outf.puts(buf)
        outf.close()
      end
    }
  else
    puts srcpath
    puts "No such directory"
  end
end
main
