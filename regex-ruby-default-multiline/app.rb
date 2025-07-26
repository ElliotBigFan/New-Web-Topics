require 'sinatra'

get '/' do
  erb :index, locals: { url: nil, result: nil }
end

post '/' do
  url = params['url']
  result = nil

  if url && !url.empty?
    if url =~ /^https/
      # Vulnerable check: In Ruby, ^ matches the beginning of a line, not the beginning of the string by default.
      # This allows multiline input to bypass the filter.
      # For example: "javascript:alert(1)\nhttps://anything" will match.
      result = "URL is valid: #{url.gsub('<', '&lt;').gsub('>', '&gt;')}"
    else
      result = "URL is invalid!"
    end
  end

  erb :index, locals: { url: url, result: result }
end 