package com.example;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.web.bind.annotation.*;
import org.springframework.expression.spel.standard.SpelExpressionParser;
import org.springframework.expression.Expression;

import javax.servlet.http.HttpSession;
import java.security.SecureRandom;
import java.util.Base64;

@SpringBootApplication
@RestController
public class SpelLabApplication {

    private static final String FLAG = "Flag{Sp3L_1nject10n_Succ3ss}";

    public static void main(String[] args) {
        SpringApplication.run(SpelLabApplication.class, args);
    }

    @GetMapping("/")
    public String form() {
        return "<form method='POST'>"
                + "<input name='input' placeholder='Try #{7*7}' style='width:300px;'/>"
                + "<button type='submit'>Submit</button>"
                + "</form>"
                + "<br><a href='/flag'>🔑 Get Session Flag</a>";
    }

    @PostMapping("/")
    public String eval(@RequestParam String input) {
        try {
            SpelExpressionParser parser = new SpelExpressionParser();
            Expression exp = parser.parseExpression(input);
            Object result = exp.getValue();

            // Nếu là Process, đọc stdout
            if (result instanceof Process) {
                Process process = (Process) result;
                java.io.InputStream is = process.getInputStream();
                java.util.Scanner s = new java.util.Scanner(is).useDelimiter("\\A");
                String output = s.hasNext() ? s.next() : "";
                return "Result:<br><pre>" + output + "</pre>";
            }

            return "Result: " + result;
        } catch (Exception e) {
            return "Error: " + e.getMessage();
        }
    }

    @GetMapping("/flag")
    public String flag(HttpSession session) {
        String key = generateRandomKey();
        session.setAttribute(key, FLAG);
        return "Flag is stored in Session with key: <b>" + key + "</b>";
    }

    private String generateRandomKey() {
        byte[] bytes = new byte[8];
        new SecureRandom().nextBytes(bytes);
        return Base64.getUrlEncoder().withoutPadding().encodeToString(bytes);
    }
}
