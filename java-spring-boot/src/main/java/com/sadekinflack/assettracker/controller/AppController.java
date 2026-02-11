package com.sadekinflack.assettracker.controller;

import com.sadekinflack.assettracker.entity.Asset;
import com.sadekinflack.assettracker.entity.IncomeSource;
import com.sadekinflack.assettracker.entity.IncomeTransaction;
import com.sadekinflack.assettracker.entity.ExpenseTransaction;
import com.sadekinflack.assettracker.service.*;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api")
public class AppController {
    private final IncomeSourceService sourceService;
    private final TransactionService transactionService;
    private final AssetService assetService;
    private final DashboardService dashboardService;

    public AppController(IncomeSourceService sourceService, TransactionService transactionService,
                         AssetService assetService, DashboardService dashboardService) {
        this.sourceService = sourceService;
        this.transactionService = transactionService;
        this.assetService = assetService;
        this.dashboardService = dashboardService;
    }

    @GetMapping("/dashboard/stats")
    public ResponseEntity<?> getStats() {
        return ResponseEntity.ok(dashboardService.getStats());
    }

    @GetMapping("/income-sources")
    public List<IncomeSource> getSources() {
        return sourceService.getAllSources();
    }

    @PostMapping("/income-sources")
    public IncomeSource createSource(@RequestBody IncomeSource source) {
        return sourceService.createSource(source);
    }

    @GetMapping("/income-transactions")
    public List<IncomeTransaction> getIncomes() {
        return transactionService.getIncomeTransactions();
    }

    @PostMapping("/income-transactions")
    public IncomeTransaction createIncome(@RequestBody IncomeTransaction tx) {
        return transactionService.createIncomeTransaction(tx);
    }

    @GetMapping("/expense-transactions")
    public List<ExpenseTransaction> getExpenses() {
        return transactionService.getExpenseTransactions();
    }

    @PostMapping("/expense-transactions")
    public ExpenseTransaction createExpense(@RequestBody ExpenseTransaction tx) {
        return transactionService.createExpenseTransaction(tx);
    }

    @GetMapping("/assets")
    public List<Asset> getAssets() {
        return assetService.getAllAssets();
    }

    @PostMapping("/assets")
    public Asset createAsset(@RequestBody Asset asset) {
        return assetService.createAsset(asset);
    }
}
