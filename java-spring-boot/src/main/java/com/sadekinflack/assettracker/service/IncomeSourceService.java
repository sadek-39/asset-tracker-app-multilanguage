package com.sadekinflack.assettracker.service;

import com.sadekinflack.assettracker.entity.IncomeSource;
import com.sadekinflack.assettracker.repository.IncomeSourceRepository;
import com.sadekinflack.assettracker.repository.UserRepository;
import com.sadekinflack.assettracker.security.SecurityUtils;
import org.springframework.stereotype.Service;
import java.util.List;

@Service
public class IncomeSourceService {
    private final IncomeSourceRepository repository;
    private final UserRepository userRepository;

    public IncomeSourceService(IncomeSourceRepository repository, UserRepository userRepository) {
        this.repository = repository;
        this.userRepository = userRepository;
    }

    public List<IncomeSource> getAllSources() {
        return repository.findByUserId(SecurityUtils.getCurrentUserId(userRepository));
    }

    public IncomeSource createSource(IncomeSource source) {
        Long userId = SecurityUtils.getCurrentUserId(userRepository);
        if (repository.findByUserId(userId).size() >= 5) {
            throw new RuntimeException("Maximum 5 sources allowed");
        }
        source.setUserId(userId);
        return repository.save(source);
    }
}
